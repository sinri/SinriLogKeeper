<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/7/12
 * Time: 17:39
 */

namespace sinri\SinriLogKeeper\controller;


use sinri\enoch\mvc\SethInterface;
use sinri\SinriLogKeeper\core\SLK;
use sinri\SinriLogKeeper\core\SLKWorker;

class DisplayAgent implements SethInterface
{
    protected $slkWorker;

    public function __construct($initData = null)
    {
        $this->slkWorker = new SLKWorker();
    }

    /**
     * @return bool
     */
    protected function checkUserAuth()
    {
        $username = SLK::getRequest('username');
        $password = SLK::getRequest('password');
        $auth = $this->slkWorker->checkUserAuth($username, $password);
        return $auth;
    }

    public function initializePage()
    {
        $useUserAuth = $this->slkWorker->checkUseUserAuth();
        $display_data = $this->slkWorker->displayData();
        $maxResultLineCount = $this->slkWorker->getMaxResultLineCount();

        SLK::sayOK([
            'useUserAuth' => $useUserAuth,
            'display_data' => $display_data,
            'maxResultLineCount' => $maxResultLineCount,
        ]);
    }

    public function load_files()
    {
        try {
            $groups = $this->slkWorker->getLogFileGroups();
            SLK::sayOK($groups);
        } catch (\Exception $e) {
            SLK::sayFail($e->getMessage());
        }
    }

    public function search_file()
    {
        try {
            $auth = $this->checkUserAuth();
            if (!$auth) {
                throw new \Exception("Log Locked.", 1);
            }
            $filename = SLK::getRequest('filename', '');
            $filter_method = SLK::getRequest('filter_method', 'text');
            $filter = SLK::getRequest('filter', '');
            $line_begin = SLK::getRequest('line_begin', 0);
            $line_end = SLK::getRequest('line_end', 0);
            $around_lines = SLK::getRequest('around_lines', 0);

            $is_readable = $this->slkWorker->checkIsReadableFile($filename);
            if (!$is_readable) {
                throw new \Exception("想搞注入攻击吗，干得好。", 1);
            }
            $list = $this->slkWorker->filterTargetFile($filename, $filter_method, $filter, $line_begin, $line_end, $around_lines, $command);
            SLK::sayOK(['list' => $list, 'command' => $command]);
        } catch (\Exception $e) {
            SLK::sayFail($e->getMessage());
        }
    }

    public function download()
    {
        try {
            $auth = $this->checkUserAuth();
            if (!$auth) {
                throw new \Exception("Log Locked.", 1);
            }
            $filename = SLK::getRequest('filename', '');
            SLK::responseFileDownload($filename);
        } catch (\Exception $e) {
            SLK::sayFail($e->getMessage());
        }
    }
}