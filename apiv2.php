<?php
require_once __DIR__ . '/autoload.php';

$lamech = new \sinri\enoch\mvc\Lamech();
$lamech->getRouter()->loadController(
    '',
    "\\sinri\\SinriLogKeeper\\controller\\DisplayAgent"
);
//$lamech->getRouter()->setDebug(true);
//$lamech->setDebug(true);
$lamech->handleRequestThroughAdah();