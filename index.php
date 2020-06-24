<?php

use App\Route;

/**
 * Include files
 */
spl_autoload_register(function ($class) {
    $baseName = explode('\\', $class);
    $baseName = array_pop($baseName);

    $files = [
        'Auth'              => '\Component\Auth.php',
        'Helper'            => '\Component\Helper.php',
        'Route'             => '\Component\Route.php',
        'ProductEntity'     => '\Model\ProductEntity.php',
        'OrderEntity'       => '\Model\OrderEntity.php',
        'DB'                => '\Repository\Adapter\DB.php',
        'ProductRepository' => '\Repository\ProductRepository.php',
        'OrderRepository'   => '\Repository\OrderRepository.php',
        'ProductService'    => '\Service\ProductService.php',
        'OrderService'      => '\Service\OrderService.php',
        'ProductController' => '\Controller\ProductController.php',
        'OrderController'   => '\Controller\OrderController.php',
        'View'              => '\View\View.php',
    ];

    if (isset($files[$baseName])) {
        include_once __DIR__ . $files[$baseName];
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
