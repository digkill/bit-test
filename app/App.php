<?php

namespace App;

use App\Services\Facade;
use Exception;

class App
{
    public static $services = [];
    public static $self;
    private $controllerNamespace = 'App\Controllers';

    public static $facades;

    public static function get()
    {
        return self::$facades;
    }

    public function __construct($services)
    {
        self::$self = $this;
        self::$facades = new Facade($services);
    }


    public function emit(): void
    {
        self::get()->session->start();
        self::get()->user->start();
        $controllerClassName = $this->controllerNamespace . '\\' . self::get()->routes->controller . 'Controller';

        if (!class_exists($controllerClassName)) {
            throw new Exception('Controller "' . self::get()->routes->controller . '" not found in ' . $this->controllerNamespace);
        }

        $controller = new $controllerClassName;
        $action = self::get()->routes->action;

        if (!method_exists($controller, $action)) {
            throw new Exception('Action  "' . self::get()->routes->action . '" not found in ' . $controllerClassName);
        }

        $controller->$action();
    }

}
