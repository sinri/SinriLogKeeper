<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/5/9
 * Time: 10:21
 */

namespace sinri\enoch\mvc;


class MiddlewareInterface
{
    /**
     * @param $class_name string class name with namespace
     * @return MiddlewareInterface
     */
    public static function MiddlewareFactory($class_name)
    {
        if (!empty($class_name)) {
            return new $class_name();
        }
        return new MiddlewareInterface();
    }

    /**
     * Check request data with $_REQUEST, $_SESSION, $_SERVER, etc.
     * And decide if the request should be accepted.
     * If return false, the request would be thrown.
     * You can pass anything into $preparedData, that controller might use it (not sure, by the realization)
     * @param $path
     * @param $method
     * @param $params
     * @param null $preparedData @since 1.3.6
     * @return bool
     */
    public function shouldAcceptRequest($path, $method, $params, &$preparedData = null)
    {
        return true;
    }
}