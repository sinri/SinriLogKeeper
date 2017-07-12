<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/7/12
 * Time: 16:17
 */

require_once __DIR__ . '/autoload.php';

$SLK_Worker = new \sinri\SinriLogKeeper\core\SLKWorker();

$act = \sinri\SinriLogKeeper\core\SLK::getRequest('act');
if($act==='load_files'){
    try {
        $groups = $SLK_Worker->getLogFileGroups();
        \sinri\SinriLogKeeper\core\SLK::sayOK($groups);
    } catch (Exception $e) {
        \sinri\SinriLogKeeper\core\SLK::sayFail($e->getMessage());
    }
}elseif($act==='search_file'){
    try {
        $username = \sinri\SinriLogKeeper\core\SLK::getRequest('username');
        $password = \sinri\SinriLogKeeper\core\SLK::getRequest('password');
        $auth = $SLK_Worker->checkUserAuth($username, $password);
        if (!$auth) {
            throw new Exception("Log Locked.", 1);
        }
        $filename = \sinri\SinriLogKeeper\core\SLK::getRequest('filename', '');
        $filter_method = \sinri\SinriLogKeeper\core\SLK::getRequest('filter_method', 'text');
        $filter = \sinri\SinriLogKeeper\core\SLK::getRequest('filter', '');
        $line_begin = \sinri\SinriLogKeeper\core\SLK::getRequest('line_begin', 0);
        $line_end = \sinri\SinriLogKeeper\core\SLK::getRequest('line_end', 0);
        $around_lines = \sinri\SinriLogKeeper\core\SLK::getRequest('around_lines', 0);

        $is_readable = $SLK_Worker->checkIsReadableFile($filename);
        if (!$is_readable) {
            throw new Exception("想搞注入攻击吗，干得好。", 1);
        }
        $list = $SLK_Worker->filterTargetFile($filename, $filter_method, $filter, $line_begin, $line_end, $around_lines, $command);
        \sinri\SinriLogKeeper\core\SLK::sayOK(['list' => $list, 'command' => $command]);
    } catch (Exception $e) {
        \sinri\SinriLogKeeper\core\SLK::sayFail($e->getMessage());
    }
}elseif($act==='download'){
    try {
        $username = \sinri\SinriLogKeeper\core\SLK::getRequest('username');
        $password = \sinri\SinriLogKeeper\core\SLK::getRequest('password');
        $auth = $SLK_Worker->checkUserAuth($username, $password);
        if (!$auth) {
            throw new Exception("Log Locked.", 1);
        }
        $filename = \sinri\SinriLogKeeper\core\SLK::getRequest('filename', '');
        \sinri\SinriLogKeeper\core\SLK::responseFileDownload($filename);
    } catch (Exception $e) {
        \sinri\SinriLogKeeper\core\SLK::sayFail($e->getMessage());
    }
} elseif ($act === 'initializePage') {
    $useUserAuth = $SLK_Worker->checkUseUserAuth();
    $display_data = $SLK_Worker->displayData();
    $maxResultLineCount = $SLK_Worker->getMaxResultLineCount();

    \sinri\SinriLogKeeper\core\SLK::sayOK([
        'useUserAuth' => $useUserAuth,
        'display_data' => $display_data,
        'maxResultLineCount' => $maxResultLineCount,
    ]);
}