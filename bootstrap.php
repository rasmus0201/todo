<?php
/*
 * Framework: Slim 3
 * Helpers: Symfony VarDumper
*/

//Load dependencies
require '../vendor/autoload.php';
require '../config.php';

//Load helper functions
require '../app/Helpers/helper.php';

use \Slim\App;
use Illuminate\Database\Capsule\Manager as Capsule;

//Create Slim App instance
$app = new App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

//Eloquent Database instance
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => DB_HOST,
    'port' => DB_PORT,
    'database' => DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASSWORD,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
]);
$capsule->bootEloquent();
$capsule->setAsGlobal();

// Get slim app container
$container = $app->getContainer();

// Register component on container
// Make Twig handle views
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../views', [
        'cache' => '../cache'
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getHost()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));

    return $view;
};
