<?php
/**
 * Created by PhpStorm.
 * User: Vieraw
 * Date: 21.01.2018
 * Time: 16:34
 */

include_once 'vendor/autoload.php';

$loggers = new SplObjectStorage();

$loggers->attach(new Log\Loggers\FileLogger([
    'path' => 'default.log',
    'availableLevels' => ['error']
]));

$log = new Log\Logger($loggers);

$log->info("Info message");
$log->alert("Alert message");
$log->error("Error message");
$log->debug("Debug message");
$log->notice("Notice message");
$log->warning("Warning message");
$log->critical("Critical message");
$log->emergency("Emergency message");