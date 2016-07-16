<?php

/**
* 
*/
class SinriLogKeeperConfig
{
	private $config_path=null;
	private $paths=null;
	private static $instance=null;

	private $useUserAuth=false;
	private $user_list=array();

	function __construct($config_path=null)
	{
		if($config_path==null){
			$this->config_path=__DIR__.'/slk.config';
		}else{
			$this->config_path=$config_path;
		}

		$this->useUserAuth=false;
		$this->user_list=array();
	}

	private function load(){
		$this->useUserAuth=false;
		$this->user_list=array();
		$this->paths=array();
		if(file_exists($this->config_path)){
			$text=file_get_contents($this->config_path);
			$lines=preg_split('/[\n\r]+/', $text);
			// print_r($lines);die();
			foreach ($lines as $line) {
				if(in_array($line[0], array('#',' ',"\t","\r","\n"))){
					continue;
				}elseif(in_array($line[0], array('!'))){
					// Options
					$items=explode(' ', $line);
					if($items[0]=='!OptionUserAuth'){
						if($items[1]=='ON'){
							$this->useUserAuth=true;
						}
					}elseif($items[0]=='!User'){
						$this->user_list[$items[1]]=$items[2];
					}
					// print_r($items);
				}else{
					$group=strstr($line, ' ',true);
					$pattern=strstr($line, ' ');
					$group=trim($group.'');
					$pattern=trim($pattern.'');

					if(empty($group) || empty($pattern)){
						continue;
					}

					$this->paths[]=array(
						'group'=>$group,
						'pattern'=>$pattern
					);
				}
			}
		}
		// $this->paths=array(
		// 	array(
		// 		'group'=>'Apache2',
		// 		'pattern'=>'/var/log/apache2/*_log'
		// 	),
		// );
		// var_dump($this->user_list);die();
	}

	public function getPaths(){
		return $this->paths;
	}

	public function isUseUserAuth(){
		return $this->useUserAuth;
	}
	public function userAuth($username=null,$password=null){
		if($this->useUserAuth){
			if($this->user_list[$username]==$password){
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}
	}

	public static function getInstance($config_path=null){
		if(SinriLogKeeperConfig::$instance==null){
			SinriLogKeeperConfig::$instance=new SinriLogKeeperConfig($config_path);
			SinriLogKeeperConfig::$instance->load();
		}
		return SinriLogKeeperConfig::$instance;
	}

}
