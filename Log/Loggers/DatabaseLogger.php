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

    /**
     * @param $value
     * @throws \InvalidArgumentException
     */
    public function setDb($value)
    {
        if (!$value instanceof \PDO)
        {
            throw new \InvalidArgumentException('To connect to the database should be used \PDO');
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
        $this->execute(
        [
            'date' => $this->getDate(),
            'level' => $level,
            'message' => $message,
            'context' => $this->stringify($context)
        ]);
    }

    /**
     * @param array $data
     */
    protected function execute(array $data)
    {
        $keys = array_keys($data);
        $sth = $this->getDb()->prepare('INSERT INTO ' . $this->table .
            ' (' . \implode(', ', $keys) . ') VALUES ( ' . \implode(', :', $keys) . ')');
        $sth->execute($data);
    }
}