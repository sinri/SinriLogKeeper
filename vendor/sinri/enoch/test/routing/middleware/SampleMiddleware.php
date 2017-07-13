<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/5/9
 * Time: 10:55
 */

namespace sinri\enoch\test\routing\middleware;


use sinri\enoch\mvc\MiddlewareInterface;

class SampleMiddleware extends MiddlewareInterface
{
    public function shouldAcceptRequest($path, $method, $params, &$preparedData = null)
    {
        //print_r([$path,$method,$params]);
        if ($params[1] == 100) {
            return false;
        } elseif ($params[1] == 50) {
            $preparedData = 50;
        } else {
            $preparedData = 10;
        }
        return true;
    }
}