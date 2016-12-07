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

	public static function filterTargetFile($filename,$filter_method='text',$filter='',$line_begin=0,$line_end=0,$around_lines=3,&$command=''){
		switch ($filter_method) {
			case 'pure_grep':
			case 'pure_grep_case_insensitive':
			case 'egrep':
				return SinriLogKeeperWorker::filterTargetFileUsingGrep($filename,$filter_method,$filter,$line_begin,$line_end,$around_lines,$command);
				break;
			case 'text':
			case 'text_case_insensitive':
			case 'regex':
				return SinriLogKeeperWorker::filterTargetFileUsingPHP($filename,$filter_method,$filter,$line_begin,$line_end,$around_lines);
				break;
			default:
				throw new Exception("总有刁民想害朕。", 1);
				break;
		}
	}

	private static function filterTargetFileUsingGrep($filename,$filter_method='pure_grep',$filter='',$line_begin=0,$line_end=0,$around_lines=3,&$command=''){
		setlocale(LC_CTYPE, "en_US.UTF-8");
		$around_lines=intval($around_lines);
		if($filter_method=='pure_grep'){
			$options="-C ".$around_lines." -m ".intval(SinriLogKeeperWorker::$max_result_line_count);
		}elseif($filter_method=='pure_grep_case_insensitive'){
			$options="-C ".$around_lines." -i -m ".intval(SinriLogKeeperWorker::$max_result_line_count);
		}elseif ($filter_method=='egrep') {
			$options="-C ".$around_lines." -E -m ".intval(SinriLogKeeperWorker::$max_result_line_count);
		}
		$line_begin=intval($line_begin);
		$line_end=intval($line_end);
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
		$command="cat -n ".escapeshellarg($filename)."|awk '{if($1>={$line_begin} && $1<={$line_end}) print $0}'|grep {$options} ".escapeshellarg($filter);
		
		exec($command,$output);

		$list=array();
		// $list['command']=$command;
		$prev_line_number=0;
		foreach ($output as $value) {
			// $ff=strpos($value, "\t");
			// if($ff!==false){
			// 	$line_number=trim(substr($value, 0,$ff));
			// 	$line_content=substr($value, $ff+1);
			// 	$list[$line_number]=$line_content;
			// }else{
			// 	return $output;
			// }

			$found=preg_match('/^\s*(\d+)\t?(.*)$/', $value,$match);
			// print_r($match);die();
			if($found){
				$line_number=$match[1];
				$line_content=$match[2];
				$list[$line_number]=$line_content;
				$prev_line_number=$line_number;
			}
			elseif($value=='--'){
				$prev_line_number+=1;
				$list[$prev_line_number]='<hr>';
			}
			else{
				// echo $value.'...'.PHP_EOL;print_r($match);die();
				return $output;
			}
		}

		return $list;
	}
	private static function filterTargetFileUsingPHP($filename,$filter_method='text',$filter='',$line_begin=0,$line_end=0,$around_lines=3){
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

			// -1 for disabled
			// 0 for waiting for match, cache lastest lines
			// plus for after match, cache lastest lines
			$around_status=0;
			$cache_around_lines=array();
		    
		    while (($buffer = fgets($handle)) !== false) {
		    	$line_number+=1;
		    	if($line_begin>0 && $line_begin>$line_number){
		    		continue;
		    	}
		    	if($line_end>0 && $line_end<$line_number){
		    		break;
		    	}
		        $line=htmlspecialchars($buffer,ENT_QUOTES);

		        $this_line_matches=false;

		        if($filter_method=='text'){
					//Simply read file, and search it
		        	if($filter==='' || false!==strstr($buffer, $filter)){
		        		//Matched
		        		$list[$line_number]=$line;
		        		$this_line_matches=true;
		        	}
				}
				elseif($filter_method=='text_case_insensitive'){
					//Simply read file, and search it but case-insensitively
		        	if($filter==='' || false!==stristr($buffer, $filter)){
		        		//Matched
		        		$list[$line_number]=$line;
		        		$this_line_matches=true;
		        	}
				}
				elseif($filter_method=='regex'){
					//Use regex
					if(preg_match('/'.$filter.'/', $buffer)){
						//Matched
		        		$list[$line_number]=$line;
		        		$this_line_matches=true;
					}
				}
				else{
					$list[$line_number]=$line;
					$around_status=-1;
					// echo __LINE__.PHP_EOL;
				}

				if($around_lines<=0 || $around_status<0){
					//disabled
					// die('miao:'.$filter_method.' ... '.$around_status);
				}elseif($around_status===0){
					//waiting for match
					if($this_line_matches){
						foreach ($cache_around_lines as $cached_line_index => $one_cached_line) {
							// echo "list[$cached_line_index]=$one_cached_line;".PHP_EOL;
							$list[$cached_line_index]=$one_cached_line;
						}
						$cache_around_lines=array();
						$around_status=$around_lines;
					}else{
						if(count($cache_around_lines)==$around_lines){
							// $key_to_remove=null;
							// foreach ($cache_around_lines as $key => $value) {
							// 	$key_to_remove=$key;
							// 	break;
							// }
							// array_keys(input)
							// unset($cache_around_lines[$key_to_remove]);
							array_splice($cache_around_lines,0,1);
						}
						$cache_around_lines[$line_number]='[AROUND LINES:'.$line_number.']'.$line;
					}
				}else{
					//after match
					// echo "list[$line_number]=$line;".PHP_EOL;
					$list[$line_number]=$line;
					$around_status=max(0,$around_status-1);
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
