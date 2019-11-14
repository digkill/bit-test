<?php

namespace App\Services;

use App\App;

class Controller
{

    public $layout = 'main';
    protected $view;

    public function __construct()
    {
        $this->view = new View($this->layout);
    }

    public function render($viewName, array $params = [])
    {
        $controller = mb_strtolower(App::get()->routes->controller);
        $viewFile = $controller . '/' . $viewName . '.php';
        $content = $this->view->renderFile($viewFile, $params);
        $this->view->renderPage($content);
    }

}