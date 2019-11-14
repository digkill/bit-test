<?php

namespace App\Services;

class Facade
{

    public $database;
    public $user;
    public $session;
    public $routes;
    public $controller;

    public function __construct($services)
    {
        foreach ($services['services'] as $service => $class) {
            $serviceName = mb_convert_case($service, MB_CASE_LOWER, 'UTF-8');
            $config = [];

            $path = __DIR__ . "/../config/{$serviceName}.php";

            if (file_exists($path)) {
                $config = require $path;
            }
            $this->$serviceName =  new $class($config);
            //self::$services[$serviceName] = new $class($config);
        }

    }
}