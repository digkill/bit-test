<?php

namespace App\Services;

use App\App;

class Model
{

    public static $tableName;


    public $id;


    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM ' . static::$tableName . ' WHERE `id` = ?';
        $stmt = App::get()->database->connection->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();


        return $result->num_rows ? static::createModel($result->fetch_array()) : null;
    }

    public static function createModel($attributes)
    {
        $model = new static();
        foreach ($attributes as $key => $value) {
            $model->$key = $value;
        }

        return $model;
    }

    public function isLoaded()
    {
        return $this->id > 0;
    }
}