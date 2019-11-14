<?php

return [
    'host' => getenv('MYSQL_HOST'),
    'dbname' => getenv('MYSQL_DATABASE'),
    'username' => getenv('MYSQL_USER'),
    'password' => getenv('MYSQL_PASSWORD'),
    'port' => getenv('MYSQL_PORT'),
];
