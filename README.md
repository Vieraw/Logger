# Logger
PSR-3 Logger
## Installation
Install the latest version with. \
In the composer.json add sections:
```bash
    "repositories":
    [
        {
            "type": "git",
            "url": "https://github.com/Vieraw/Logger.git"
        }
    ],
    "require":
    {
        "Vieraw/Logger": "dev-master"
    },
```

Execute:
```bash
$ composer install
```


## Basic Usage

```php
<?php

    include_once 'vendor/autoload.php';

    use Psr\Log\LogLevel;
    try
    {
        $loggers = new SplObjectStorage();
        
        //Logger connections to the database
            
        $loggers->attach(new Log\Loggers\DatabaseLogger([
            'db' => new \PDO('mysql:host=localhost;dbname=db;', 'username', 'password', [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']),
            'table' => 'logs'
        ]));
        
        //Logger connections to the file
        
        $loggers->attach(new Log\Loggers\FileLogger([
            'path' => 'default.log'
        ]));
        
        //Logger connections to the mail
        //Used only on error
        
        $loggers->attach(new Log\Loggers\MailNativeLogger([
            'to' => 'email@mail.com',
            'subject' => 'MailLoggerError',
            'from' => 'from@mail.com',
            'levels' => [LogLevel::ERROR]
        ]));
        
        $log = new Log\Logger($loggers);
        
        // add records to the log
        
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
```