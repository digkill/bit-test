<?php

namespace App\Services;

class Database
{

    public $connection;

    public function __toString()
    {
        return __CLASS__;
    }

    public function __construct($config)
    {
        $this->connection = new \mysqli($config['host'], $config['username'], $config['password'], $config['dbname'], $config['port']);

        if ($this->connection->connect_errno) {
            throw new \Exception(
                $this->connection->connect_errno
            );
        }
        return $this->connection;
    }

    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}