<?php
require 'config.php';

// Configure DB Migration handler
return [
    'paths' => [
        'migrations' => 'migrations'
    ],
    'migration_base_class' => '\Todo\Migration\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => 'mysql',
            'host' => DB_HOST,
            'name' => DB_NAME,
            'user' => DB_USER,
            'pass' => DB_PASSWORD,
            'port' => DB_PORT
        ]
    ]
];
