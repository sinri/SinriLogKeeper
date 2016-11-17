<?php
require_once('config.php');
/**
* Sinri Log Keeper
*/
class SinriLogKeeperWorker
{
	private static $max_result_line_count=2000;

	function __construct()
	{
		# code...
	}

	public static function getMaxResultLineCount(){
		return SinriLogKeeperWorker::$max_result_line_count;
	}

	public static function getLogFileGroups(){
		$paths=SinriLogKeeperConfig::getInstance()->getPaths();
		$all_files=array();
		foreach ($paths as $path_item) {
			$files=glob($path_item['pattern']);
			// $all_files=array_merge($all_files,$files);
			$all_files[$path_item['group']]=$files;
		}
		return $all_files;
	}

	public static function checkIsReadableFile($filename){
		$paths=SinriLogKeeperConfig::getInstance()->getPaths();
		//Method One
		/*		
		foreach ($paths as $path_item) {
			$files=glob($path_item['pattern']);
			if(in_array($filename, $files)){
				return true;
			}
		}
		return false;
		*/
		//Method Two
		foreach ($paths as $path_item) {
			$is_match=fnmatch($path_item['pattern'], $filename);
			if($is_match)return true;
		}
		return false;
	}

	public static function checkUseUserAuth(){
		return SinriLogKeeperConfig::getInstance()->isUseUserAuth();
	}

	public static function checkUserAuth($username,$password){
		$r = SinriLogKeeperConfig::getInstance()->userAuth($username,$password);
		return $r;
	}

	public static function displayData(){
		$displayData=SinriLogKeeperConfig::getInstance()->getDisplayData();
		if(!$displayData){
			$displayData=array(
				'title'=>'SampleProject',
				'header'=>'SampleProject',
			);
		}
		return $displayData;
	}

	public static function filterTargetFile($filename,$filter_method='text',$filter='',$line_begin=0,$line_end=0){
		switch ($filter_method) {
			case 'pure_grep':
			case 'pure_grep_case_insensitive':
			case 'egrep':
				return SinriLogKeeperWorker::filterTargetFile_pure_grep($filename,$filter_method,$filter,$line_begin,$line_end);
				break;
			case 'text':
			case 'text_case_insensitive':
			case 'regex':
				return SinriLogKeeperWorker::filterTargetFile_use_php($filename,$filter_method,$filter,$line_begin,$line_end);
				break;
			default:
				throw new Exception("总有刁民想害朕。", 1);
				break;
		}
	}

	private static function filterTargetFile_pure_grep($filename,$filter_method='pure_grep',$filter='',$line_begin=0,$line_end=0){
		setlocale(LC_CTYPE, "en_US.UTF-8");
		if($filter_method=='pure_grep'){
			$options="";
		}elseif($filter_method=='pure_grep_case_insensitive'){
			$options="-i";
		}elseif ($filter_method=='egrep') {
			$options="-E";
		}
		$line_begin=intval($line_begin);
		$line_end=intval($line_end);
		/*
		// method one
		$line_diff=abs($line_end-$line_begin);
		if($line_begin==0 && $line_end==0){
			$content_range="";
		}elseif(0<=$line_begin && $line_begin<=$line_end){
			$content_range="head -n {$line_end}|tail -n {$line_diff}|";
		}elseif($line_begin<=$line_end && $line_end<=0){
			$abs_line_begin=-$line_begin;
			$content_range="tail -n {$abs_line_begin}|tail -n {$line_diff}|";
		}else{
			$content_range="echo 'RANGE ERROR, FALLING BACK TO GREP ENTIRE';";
		}
		*/
		/*
		//method two
		if($line_begin==0 && $line_end==0){
			$command="grep {$options} ".escapeshellarg($filter)." ".escapeshellarg($filename)."| head -n ".escapeshellarg(SinriLogKeeperWorker::$max_result_line_count);
		}else{
			$total_lines=exec("cat ".escapeshellarg($filename)."|wc -l");
			$total_lines=intval($total_lines);
			if($line_begin<0){
				$line_begin+=$total_lines;
			}
			if($line_end<0){
				$line_end+=$total_lines;
			}
			if($line_end==0){
				$line_end=$total_lines;
			}
			$line_diff=abs($line_end-$line_begin);
			$content_range="head -n {$line_end} ".escapeshellarg($filename)."|tail -n {$line_diff}|";
			$command=$content_range." grep {$options} ".escapeshellarg($filter)."| head -n ".escapeshellarg(SinriLogKeeperWorker::$max_result_line_count);
		}
		*/
		//method three
		$total_lines=exec("cat ".escapeshellarg($filename)."|wc -l");
		$total_lines=intval($total_lines);
		if($line_begin<0){
			$line_begin+=$total_lines;
		}
		if($line_end<0){
			$line_end+=$total_lines;
		}
		if($line_end==0){
			$line_end=$total_lines;
		}
		$command="cat -n ".escapeshellarg($filename)."|awk '{if($1>={$line_begin} && $1<={$line_end}) print $0}'|grep {$options} ".escapeshellarg($filter)."|head -n ".escapeshellarg(SinriLogKeeperWorker::$max_result_line_count);

		
		exec($command,$output);
		//return $output;

		$list=array();
		// $list['command']=$command;
		foreach ($output as $key => $value) {
			$ff=strpos($value, "\t");
			if($ff!==false){
				$line_number=trim(substr($value, 0,$ff));
				$line_content=substr($value, $ff+1);
				$list[$line_number]=$line_content;
			}else{
				return $output;
			}
		}

		return $list;
	}
	private static function filterTargetFile_use_php($filename,$filter_method='text',$filter='',$line_begin=0,$line_end=0){
		$handle = fopen($filename, "r");
		$list=array();
		if ($handle) {
			$line_number=0;
			if($line_begin<0 || $line_end<0){
				$total_lines=SinriLogKeeperWorker::getLineCountOfFile($filename);
				if($line_begin<0){
					$line_begin=$total_lines+$line_begin;
				}
				if($line_end<0){
					$line_end=$total_lines+$line_end;
				}
			}
		    while (($buffer = fgets($handle)) !== false) {
		    	$line_number+=1;
		    	if($line_begin>0 && $line_begin>$line_number){
		    		continue;
		    	}
		    	if($line_end>0 && $line_end<$line_number){
		    		break;
		    	}
		        $line=htmlspecialchars($buffer,ENT_QUOTES);
		        if($filter_method=='text'){
					//Simply read file, and search it
		        	if($filter==='' || false!==strstr($buffer, $filter)){
		        		//Matched
		        		$list[$line_number]=$line;
		        	}
				}
				elseif($filter_method=='text_case_insensitive'){
					//Simply read file, and search it but case-insensitively
		        	if($filter==='' || false!==stristr($buffer, $filter)){
		        		//Matched
		        		$list[$line_number]=$line;
		        	}
				}
				elseif($filter_method=='regex'){
					//Use regex
					if(preg_match('/'.$filter.'/', $buffer)){
						//Matched
		        		$list[$line_number]=$line;
					}
				}
				else{
					$list[$line_number]=$line;
				}
				if(count($list)>SinriLogKeeperWorker::$max_result_line_count){
					$list['NOTE']='The result contains lines beyond the limitation so that stopped search. Above might not be all results, use line range settings to find more.';
					break;
				}
		    }
		    // if (!feof($handle)) {
		    //     echo "Error: unexpected fgets() fail\n";
		    // }
		    fclose($handle);
		    return $list;
		}else{
			throw new Exception("Error Reading File", 1);		
		}
	}

	public static function getLineCountOfFile($filename){
		$file = new SplFileObject($filename, 'r');
		$file->seek(PHP_INT_MAX);
		return ($file->key() + 0); 
	}
}
