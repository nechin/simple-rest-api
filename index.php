<?php

use App\Route;

/**
 * Include files
 */
spl_autoload_register(function ($class) {
    $baseName = explode('\\', $class);
    $baseName = array_pop($baseName);

    $ds = DIRECTORY_SEPARATOR;

    $files = [
        'Auth'              => 'Component' . $ds . 'Auth.php',
        'Helper'            => 'Component' . $ds . 'Helper.php',
        'Route'             => 'Component' . $ds . 'Route.php',
        'ProductEntity'     => 'Model' . $ds . 'ProductEntity.php',
        'OrderEntity'       => 'Model' . $ds . 'OrderEntity.php',
        'DB'                => 'Repository' . $ds . 'Adapter' . $ds . 'DB.php',
        'ProductRepository' => 'Repository' . $ds . 'ProductRepository.php',
        'OrderRepository'   => 'Repository' . $ds . 'OrderRepository.php',
        'ProductService'    => 'Service' . $ds . 'ProductService.php',
        'OrderService'      => 'Service' . $ds . 'OrderService.php',
        'ProductController' => 'Controller' . $ds . 'ProductController.php',
        'OrderController'   => 'Controller' . $ds . 'OrderController.php',
        'View'              => 'View' . $ds . 'View.php',
    ];

    if (isset($files[$baseName])) {
        include_once __DIR__ . $ds . $files[$baseName];
    }
});

/**
 * Available routes
 */
$apiRoutes = [
    '/api/generate'     => 'ProductController@generate@GET',
    '/api/product/all'  => 'ProductController@getAll@GET',
    '/api/order/create' => 'OrderController@create@POST',
    '/api/order/pay'    => 'OrderController@pay@POST',
];

$route = new Route();
$route->setRoutes($apiRoutes);
$route->route();
