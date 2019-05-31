<?php
/*
 * Project: Todo App
 * Framework: Slim 3
 * Helpers: Symfony VarDumper, Eloquent, Twig, Vue.js
*/

require '../vendor/autoload.php';

use \Slim\App;
use Illuminate\Database\Capsule\Manager as Capsule;

require '../config.php';
require '../app/Helpers/helper.php';

$app = new App([
    'settings' => [
        'displayErrorDetails' => true // Debug on
    ]
]);

// Eloquent Database instance
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => DB_HOST,
    'port' => DB_PORT,
    'database' => DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASSWORD,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci'
]);
$capsule->bootEloquent();
$capsule->setAsGlobal();

$container = $app->getContainer();

// Make Twig handle views
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../views', [
        'cache' => '../cache'
    ]);

    // Add base url to twig as an extension for easy use
    $baseURL = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getHost() . $container->get('request')->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $baseURL));

    return $view;
};

// Custom 404 page
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']->render($response->withStatus(404), 'error/404.html.twig');
    };
};
