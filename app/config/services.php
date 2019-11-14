<?php

use App\Services\Controller;
use App\Services\Database;
use App\Services\Session;
use App\Services\User;
use App\Services\Routes;

return [
    'Database' => Database::class,
    'Controller' => Controller::class,
    'Session' => Session::class,
    'User' => User::class,
    'Routes' => Routes::class,
];