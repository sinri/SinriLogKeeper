<?php

namespace sinri\SinriLogKeeper\core;

//require_once('config.php');
use sinri\enoch\mvc\BaseCodedException;

/**
* Sinri Log Keeper
*/
class SLKWorker
{
	private $max_result_line_count=2000;

	function __construct()
	{
		# code...
	}

    /**
     * @return int
     */
	public function getMaxResultLineCount(){
		return $this->max_result_line_count;
	}

    /**
     * @return array
     */
	public function getLogFileGroups(){
        $paths = SLKConfig::getInstance()->getPaths();
		$all_files=array();
		foreach ($paths as $path_item) {
			$files=glob($path_item['pattern']);
			$all_files[$path_item['group']]=$files;
		}
		return $all_files;
	}

    /**
     * @param $filename
     * @return bool
     */
	public function checkIsReadableFile($filename){
        $paths = SLKConfig::getInstance()->getPaths();
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

    /**
     * @return bool
     */
	public function checkUseUserAuth(){
        return SLKConfig::getInstance()->isUseUserAuth();
	}

    /**
     * @param $username
     * @param $password
     * @return bool
     */
	public function checkUserAuth($username,$password){
        $r = SLKConfig::getInstance()->userAuth($username, $password);
		return $r;
	}

    /**
     * @return array
     */
	public function displayData(){
        $displayData = SLKConfig::getInstance()->getDisplayData();
		if(!$displayData){
			$displayData=array(
				'title'=>'SampleProject',
				'header'=>'SampleProject',
			);
		}
		return $displayData;
	}

    /**
     * @param $filename
     * @param string $filter_method
     * @param string $filter
     * @param int $line_begin
     * @param int $line_end
     * @param int $around_lines
     * @param string $command
     * @return array
     * @throws BaseCodedException
     */
	public function filterTargetFile($filename,$filter_method='text',$filter='',$line_begin=0,$line_end=0,$around_lines=3,&$command=''){
		switch ($filter_method) {
			case 'pure_grep':
			case 'pure_grep_case_insensitive':
			case 'egrep':
				return $this->filterTargetFileUsingGrep($filename,$filter_method,$filter,$line_begin,$line_end,$around_lines,$command);
				break;
			case 'text':
			case 'text_case_insensitive':
			case 'regex':
				return $this->filterTargetFileUsingPHP($filename,$filter_method,$filter,$line_begin,$line_end,$around_lines);
				break;
			default:
                throw new BaseCodedException("总有刁民想害朕。", BaseCodedException::NOT_IMPLEMENT_ERROR);
				break;
		}
	}

    /**
     * @param $filename
     * @param string $filter_method
     * @param string $filter
     * @param int $line_begin
     * @param int $line_end
     * @param int $around_lines
     * @param string $command
     * @return array
     */
	private function filterTargetFileUsingGrep($filename,$filter_method='pure_grep',$filter='',$line_begin=0,$line_end=0,$around_lines=3,&$command=''){
		setlocale(LC_CTYPE, "en_US.UTF-8");
		$around_lines=intval($around_lines);
        $options = "";
		if($filter_method=='pure_grep'){
			$options="-C ".$around_lines." -m ".intval($this->max_result_line_count);
		}elseif($filter_method=='pure_grep_case_insensitive'){
			$options="-C ".$around_lines." -i -m ".intval($this->max_result_line_count);
		}elseif ($filter_method=='egrep') {
			$options="-C ".$around_lines." -E -m ".intval($this->max_result_line_count);
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

	/////

    /**
     * @param $filename
     * @param string $filter_method
     * @param string $filter
     * @param int $line_begin
     * @param int $line_end
     * @param int $around_lines
     * @return array
     * @throws BaseCodedException
     */
	private function filterTargetFileUsingPHP($filename,$filter_method='text',$filter='',$line_begin=0,$line_end=0,$around_lines=3){
		$handle = fopen($filename, "r");
		$list=array();
		if (!$handle) {
            throw new BaseCodedException("Error Reading File", BaseCodedException::DEFAULT_ERROR);
		}
		$line_number=0;
		if($line_begin<0 || $line_end<0){
			$total_lines=$this->getLineCountOfFile($filename);
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

	        if($filter_method=='text'){ //Simply read file, and search it
	        	if($filter==='' || false!==strstr($buffer, $filter)){ //Matched
	        		$list[$line_number]=$line;
	        		$this_line_matches=true;
	        	}
			}
			elseif($filter_method=='text_case_insensitive'){ //Simply read file, and search it but case-insensitively
	        	if($filter==='' || false!==stristr($buffer, $filter)){ //Matched
	        		$list[$line_number]=$line;
	        		$this_line_matches=true;
	        	}
			}
			elseif($filter_method=='regex'){ //Use regex
				if(preg_match('/'.$filter.'/', $buffer)){ //Matched
	        		$list[$line_number]=$line;
	        		$this_line_matches=true;
				}
			}
			else{
				$list[$line_number]=$line;
				$around_status=-1;
			}

			if($around_lines<=0 || $around_status<0){
				//disabled
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
						$cache_around_lines=array_slice($cache_around_lines,1,count($cache_around_lines)-2,true);
					}
					$cache_around_lines[$line_number]='[AROUND:'.$line_number.']'.$line;
				}
			}else{
				//after match
				$list[$line_number]='[AROUND:'.$line_number.']'.$line;
				$around_status=max(0,$around_status-1);
			}

			if(count($list)>$this->max_result_line_count){
				$list['NOTE']='The result contains lines beyond the limitation so that stopped search. Above might not be all results, use line range settings to find more.';
				break;
			}
	    }
	    // if (!feof($handle)) {
	    //     echo "Error: unexpected fgets() fail\n";
	    // }
	    fclose($handle);
	    return $list;
	}

    /**
     * @param $filename
     * @return int
     */
	public function getLineCountOfFile($filename){
        $file = new \SplFileObject($filename, 'r');
		$file->seek(PHP_INT_MAX);
		return ($file->key() + 0); 
	}
}
