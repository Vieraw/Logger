<?php
include_once 'vendor/autoload.php';

try
{
    $loggers = new SplObjectStorage();

    $loggers->attach(new Log\Loggers\DatabaseLogger([
        'db' => (function () {
            $pdo = new \PDO('mysql:host=localhost;dbname=db;', 'username', 'password');
            $pdo->exec('set names utf8');
            return $pdo;
        })(),
        'table' => 'logs'
    ]));

    $loggers->attach(new Log\Loggers\FileLogger([
        'path' => 'default.log'
    ]));

    $loggers->attach(new Log\Loggers\MailNativeLogger([
        'to' => 'email@mail.com',
        'subject' => 'MailLoggerError',
        'from' => 'from@mail.com',
        'levels' => ['error']
    ]));
    $log = new Log\Logger($loggers);

    $log->info('Info message');
    $log->alert('Alert message');
    $log->error('Error message');
    $log->debug('Debug message');
    $log->notice('Notice message');
    $log->warning('Warning message');
    $log->critical('Critical message');
    $log->emergency('Emergency message');
}
catch (Throwable $e)
{
    echo $e->getMessage();
}