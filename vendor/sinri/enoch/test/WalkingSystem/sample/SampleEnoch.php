<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2017/3/27
 * Time: 10:25
 */

namespace sinri\enoch\test\WalkingSystem\sample;

use sinri\enoch\core\Enoch;

class SampleEnoch extends Enoch
{
    public function __construct()
    {
        parent::__construct();
        $this->initialize('SampleEnoch', __DIR__);
    }

    public function readConfig()
    {
        // Change the auto-generated stub, if needed
        parent::readConfig();
    }

    protected function getWalkerInstance($walkerName)
    {
        $class_name = "sinri\\enoch\\test\\WalkingSystem\\sample\\" . $walkerName . 'Walker';
        $walker=new $class_name($this->config);
        return $walker;
    }
}
