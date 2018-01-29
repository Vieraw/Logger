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
            if($reflection->hasMethod('set' . $name))
            {
                $this->{'set' . $name}($value);
            }
            elseif ($property->isPublic())
            {
                $this->{$name} = $value;
            }
        }
    }

    /**
     * @param $level
     * @return bool
     */
    public function isAvailable($level)
    {
        return $this->enabled && ($this->levels === null || \in_array($level, $this->levels, true));
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

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter))
        {
            return $this->{$getter};
        }
        if (method_exists($this,'set' . $name))
        {
            throw new \Exception('Getting write-only property: ' . \get_class($this) . '::' . $name);
        }

        throw new \Exception('Getting unknown property: ' . \get_class($this) . '::' . $name);
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter))
        {
            $this->{$setter}($value);
        }
        elseif (method_exists($this, 'get' . $name))
        {
            throw new \Exception('Setting read-only property: ' . \get_class($this) . '::' . $name);
        }
        else
        {
            throw new \Exception('Setting unknown property: ' . \get_class($this) . '::' . $name);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter))
        {
            return $this->{$getter}() !== null;
        }
        return false;
    }

}