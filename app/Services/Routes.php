<?php

namespace App\Services;

use App\App;

class Routes
{

    public $baseUrl;
    public $controller;
    public $action;

    public function __construct($config)
    {

        $this->baseUrl = $config['baseUrl'];
        $this->controller = $config['controller'];
        $this->action = $config['action'];

        $routes = explode('/', $_SERVER['REQUEST_URI']);

        if (!empty($routes[1])) {
            $this->controller = mb_convert_case($routes[1], MB_CASE_TITLE, 'UTF-8');
        }

        if (!empty($routes[2])) {
            $this->action = mb_convert_case($routes[2], MB_CASE_TITLE, 'UTF-8');
        }

    }

    public function toUrl($route='', $params = [])
    {
        header('Location: ' . $this->makeUrl($route, $params));
        exit;
    }

    public function makeUrl($route, array $params = [], $absolute = false)
    {
        return ($absolute ? $this->baseUrl : '/') . '' . $route . (count($params) ? '&' . http_build_query($params) : '');
    }

}