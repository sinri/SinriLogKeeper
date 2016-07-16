<?php

/**
* 
*/
class SinriLogKeeperConfig
{
	private $config_path=null;
	private $paths=null;
	private static $instance=null;

	function __construct($config_path=null)
	{
		if($config_path==null){
			$this->config_path=__DIR__.'/slk.config';
		}else{
			$this->config_path=$config_path;
		}
	}

	private function load(){
		$this->paths=array();
		if(file_exists($this->config_path)){
			$text=file_get_contents($this->config_path);
			$lines=preg_split('/[\n\r]+/', $text);
			// print_r($lines);die();
			foreach ($lines as $line) {
				if(in_array($line[0], array('#',' ',"\t","\r","\n"))){
					continue;
				}
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
		// $this->paths=array(
		// 	array(
		// 		'group'=>'Apache2',
		// 		'pattern'=>'/var/log/apache2/*_log'
		// 	),
		// );
		// var_dump($this->paths);die();
	}

	public function getPaths(){
		return $this->paths;
	}

	public static function getInstance($config_path=null){
		if(SinriLogKeeperConfig::$instance==null){
			SinriLogKeeperConfig::$instance=new SinriLogKeeperConfig($config_path);
			SinriLogKeeperConfig::$instance->load();
		}
		return SinriLogKeeperConfig::$instance;
	}

}
