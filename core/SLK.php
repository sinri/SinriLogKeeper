<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/7/12
 * Time: 16:00
 */

namespace sinri\SinriLogKeeper\core;


use sinri\enoch\core\LibRequest;
use sinri\enoch\core\LibResponse;

class SLK
{
    /**
     * @var LibRequest
     */
    protected static $requestInstance;

    /**
     * @return LibRequest
     */
    public static function request()
    {
        if (!self::$requestInstance) {
            self::$requestInstance = new LibRequest();
        }
        return self::$requestInstance;
    }

    /**
     * @var LibResponse
     */
    protected static $responseInstance;

    /**
     * @return LibResponse
     */
    public static function response()
    {
        if (!self::$responseInstance) {
            self::$responseInstance = new LibResponse();
        }
        return self::$responseInstance;
    }

    ///////

    /**
     * @param $name
     * @param null $default
     * @param null $regex
     * @param int $error
     * @return mixed
     */
    public static function getRequest($name, $default = null, $regex = null, &$error = 0)
    {
        return self::request()->getRequest($name, $default, $regex, $error);
    }

    /**
     * @param $file
     */
    public static function responseFileDownload($file)
    {
        // header("Content-type:text/html;charset=utf-8");
        $done = downloadFileAsName($file, null, $error);
        if (!$done) {
            echo $error;
        }
        // exit();
    }

    /**
     * 文件下载
     * @param $file
     * @param null $down_name
     * @param string $error
     * @return bool
     */
    public static function downloadFileAsName($file, $down_name = null, &$error = '')
    {
        if ($down_name !== null && $down_name !== false) {
            $suffix = substr($file, strrpos($file, '.')); //获取文件后缀
            $down_name = $down_name . $suffix; //新文件名，就是下载后的名字
        } else {
            $k = pathinfo($file);
            $down_name = $k['filename'] . '.' . $k['extension'];
        }

        //判断给定的文件存在与否
        if (!file_exists($file)) {
            $error = "您要下载的文件已不存在，可能是被删除";
            return false;
        }
        $fp = fopen($file, "r");
        $file_size = filesize($file);
        //下载文件需要用到的头
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length:" . $file_size);
        header("Content-Disposition: attachment; filename=" . $down_name);
        $buffer = 1024;
        $file_count = 0;
        //向浏览器返回数据
        while (!feof($fp) && $file_count < $file_size) {
            $file_con = fread($fp, $buffer);
            $file_count += $buffer;
            echo $file_con;
        }
        fclose($fp);
        return true;
    }

    /**
     * @param $data
     */
    public static function sayOK($data = '')
    {
        SLK::response()->jsonForAjax("ok", $data);
    }

    /**
     * @param $data
     */
    public static function sayFail($data = "")
    {
        SLK::response()->jsonForAjax("fail", $data);
    }

}