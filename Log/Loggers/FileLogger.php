<?php
/**
 * Created by PhpStorm.
 * User: Vieraw
 * Date: 21.01.2018
 * Time: 16:12
 */

namespace Log\Loggers;


class FileLogger extends Base
{
    public $path;
    public $pattern = '{date} {level} {message} {context}';

    /**
     * FileLogger constructor.
     * @param array $params
     * @throws \ReflectionException
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);

        if (!file_exists($this->path))
        {
            touch($this->path);
        }
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        file_put_contents($this->path, trim(strtr($this->pattern,
            [
                '{date}' => $this->getDate(),
                '{level}' => $level,
                '{message}' => $this->interpolate($message, $context),
                '{context}' => $this->stringify($context)
            ])) . PHP_EOL, FILE_APPEND);
    }
}