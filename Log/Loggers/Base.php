<?php
/**
 * Created by PhpStorm.
 * User: Vieraw
 * Date: 21.01.2018
 * Time: 16:00
 */

namespace Log\Loggers;

use Psr\Log\AbstractLogger;

abstract class Base extends AbstractLogger
{
    public $enabled = true;
    public $dateFormat = 'Y-m-d H:i';
    public $levels;

    /**
     * Base constructor.
     * @param array $params
     * @throws \ReflectionException
     */
    public function __construct(array $params = [])
    {
        $reflection = new \ReflectionClass($this);
        foreach ($params as $name => $value)
        {
            $property = $reflection->getProperty($name);
            if ($property->isPublic())
            {
                $property->setValue($this, $value);
            }
        }
    }

    /**
     * @param $level
     * @return bool
     */
    public function isAvailable($level)
    {
        if ($this->availableLevels === null || in_array($level, $this->availableLevels))
        {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    protected function getDate()
    {
        return (new \DateTime())->format($this->dateFormat);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function stringify(array $data = [])
    {
        return $data !== [] ? json_encode($data) : '';
    }


}