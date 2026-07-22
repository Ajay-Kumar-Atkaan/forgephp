<?php
const ENV_PREFIX = 'DATABASE';
return [
    /**
     * Default database connection type
     */
    'default' => env('DB_CONNECTION','mysql'),

    /**
     * connection configration lets start with mysql 
     */

    'connections' => [
        'mysql' => [
            'url' => env(ENV_PREFIX.'_URL'),
            'host' => env(ENV_PREFIX.'_HOST','127.0.0.1'),
            'port' => env(ENV_PREFIX.'_PORT','3306'),
            'database' => env(ENV_PREFIX.'_NAME') ?? env('APP_NAME',''),
            'username' => env(ENV_PREFIX.'_USERNAME') ?? env('APP_NAME',''),
            'password' => env(ENV_PREFIX.'_PASSWORD',''),
        ]
    ]
];