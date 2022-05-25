<?php

return [
    'db' => [
        'driver'   => getenv('DB_DRIVER'),
        'hostname' => getenv('DB_HOSTNAME'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'name'     => getenv('DB_NAME')
    ],
    'env' => getenv('ENV')
];
