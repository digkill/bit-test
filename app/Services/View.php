<?php

namespace App\Services;

use Exception;

class View
{
    public $layout;

    public $layoutName = 'main';

    protected $layoutsPath = APP_PATH . 'Views/layouts/';
    protected $viewsPath = APP_PATH . 'Views/';

    public function __construct($layout)
    {
        $this->layout = $layout;
    }


    public function renderFile($viewFile, array $params = [])
    {
        $viewFileName = $this->viewsPath . $viewFile;


        if (!file_exists($viewFileName)) {
            throw new Exception('View "' . $viewFile . '" is not found');
        }

        if (is_array($params)) {
            extract($params, EXTR_SKIP);
        }

        ob_start();
        require $viewFileName;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function renderPage($content = '')
    {
        $layoutFileName = $this->layoutsPath . $this->layoutName . '.php';



        if (!file_exists($layoutFileName)) {
            throw new Exception('Layout ' . $this->layoutName . ' is not found');
        }

        require_once $layoutFileName;
    }

}