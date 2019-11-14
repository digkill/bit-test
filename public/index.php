<?php

use App\App;

chdir(dirname(__DIR__));
define('APP_PATH', __DIR__ . '/../app/');

require 'vendor/autoload.php';

if (file_exists('.env')) {
    (new Symfony\Component\Dotenv\Dotenv())->load(__DIR__ . '/../.env');
}

$services = ['services' => require './app/config/services.php'];

try {
    (new App($services))->emit();
} catch (Exception $e) {
    var_dump($e->getMessage());
}

