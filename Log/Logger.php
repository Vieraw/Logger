<?php
/**
 * Created by PhpStorm.
 * User: Vieraw
 * Date: 21.01.2018
 * Time: 15:52
 */

namespace Log;

use Psr\Log\AbstractLogger,
    Log\Loggers\Base;

class Logger extends AbstractLogger
{
    private $loggers;

    public function __construct(\Iterator $loggers)
    {
        $this->loggers = $loggers;
    }

    public function log($level, $message, array $context = [])
    {
        foreach ($this->loggers as $logger)
        {
            if (!$logger instanceof Base || !$logger->isAvailable($level))
            {
                continue;
            }
            $logger->log($level, $message, $context);
        }
    }
}