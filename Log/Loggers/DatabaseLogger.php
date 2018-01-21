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
    public $dsn;
    public $username;
    public $password;
    public $table;
    private $db;

    /**
     * DatabaseLogger constructor.
     * @param array $attributes
     * @throws \ReflectionException
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->db = new \PDO($this->dsn, $this->username, $this->password);
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $data =
        [
            'date' => $this->getDate(),
            'level' => $level,
            'message' => $message,
            'context' => $this->stringify($context)
        ];
        $sth = $this->db->prepare(
            'INSERT INTO ' . $this->table . ' (date, level, message, context) ' .
            'VALUES (:date, :level, :message, :context)'
        );
        $sth->execute($data);
    }
}