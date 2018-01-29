<?php
/**
 * Created by PhpStorm.
 * User: Vieraw
 * Date: 21.01.2018
 * Time: 17:03
 */

namespace Log\Loggers;

class DatabaseLogger extends Base
{
    public $table;
    /**
     * @var \PDO
     */
    protected $db;

    public function setDb($value)
    {
        if (!$value instanceof \PDO)
        {
            throw new \InvalidArgumentException('');
        }
        $this->db = $value;
    }

    /**
     * @return \PDO
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $sth = $this->getDb()->prepare(
            'INSERT INTO ' . $this->table . ' (date, level, message, context) ' .
            'VALUES (:date, :level, :message, :context)'
        );
        $sth->execute(
        [
            'date' => $this->getDate(),
            'level' => $level,
            'message' => $message,
            'context' => $this->stringify($context)
        ]);
    }
}