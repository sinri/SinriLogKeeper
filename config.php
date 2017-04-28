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

	private $display=array();

	function __construct($config_path=null)
	{
		$this->config_path=$config_path;
		if($config_path==null){
			$this->config_path=__DIR__.'/slk.config.php';
		}

		$this->useUserAuth=false;
		$this->user_list=array();
		$this->display=array();
	}

	protected function load(){
		$this->useUserAuth=false;
		$this->user_list=array();
		$this->paths=array();
		$this->display=array(
			'title'=>'SampleProject',
			'header'=>'SampleProject',
			'special'=>'',
		);
		if(file_exists($this->config_path)){
			$text=file_get_contents($this->config_path);
			$lines=preg_split('/[\n\r]+/', $text);
			// print_r($lines);die();
			foreach ($lines as $line) {
				if(empty($line)){
					continue;
				}
				if(in_array($line[0], array('<','#',' ',"\t","\r","\n"))){
					continue;
				}
				elseif(in_array($line[0], array('!'))){
					// Options
					$items=explode(' ', $line);
					if($items[0]=='!OptionUserAuth'){
						if($items[1]=='ON'){
							$this->useUserAuth=true;
						}
					}
					elseif($items[0]=='!User'){
						$this->user_list[$items[1]]=$items[2];
					}
					elseif($items[0]=='!OptionTitle'){
						$this->display['title']=$items[1];
					}
					elseif($items[0]=='!OptionHeader'){
						$this->display['header']=$items[1];
					}elseif($items[0]=='!OptionSpecial'){
						$this->display['special']=$items[1];
					}
					// print_r($items);
				}
				else{
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
			if(isset($this->user_list[$username]) && $this->user_list[$username]==$password){
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}
	}

	public function getDisplayData(){
		return $this->display;
	}

	public static function getInstance($config_path=null){
		if(SinriLogKeeperConfig::$instance==null){
			$slkc=new SinriLogKeeperConfig($config_path);
			$slkc->load();
			SinriLogKeeperConfig::$instance=$slkc;
		}
		return SinriLogKeeperConfig::$instance;
	}

}
