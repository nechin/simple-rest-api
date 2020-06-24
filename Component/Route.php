<?php

namespace App;

use App\Controller\ProductController;

/**
 * Class Route
 * @package App
 */
class Route
{
    /**
     * @var mixed|string
     */
    private $request;

    /**
     * @var array
     */
    private $routeClasses;

    /**
     * @var array
     */
    private $routes;

    /**
     * Route constructor.
     */
    public function __construct()
    {
        // Handle request URI
        $this->request = $_SERVER['REQUEST_URI'];
        $this->routeClasses = ['ProductController', 'OrderController'];
    }

    /**
     * Set routes
     *
     * @param array $routes
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    /**
     * Get routes
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Check route
     *
     * @param $requestUri
     * @return bool
     */
    private function checkRouting($requestUri)
    {
        if (isset($this->routes[$requestUri])) {
            return true;
        }

        return false;
    }

    /**
     * Route request
     */
    public function route()
    {
        if ($this->checkRouting($this->request)) {
            /**
             * Check auth header token
             */
            if (!(new Auth())->check()) {
                Helper::badRequest('Access is denied. Authenticates.');
            }

            $method = Helper::getRequestMethod();
            list($className, $classMethod, $requestMethod) = explode('@', $this->routes[$this->request]);

            if (in_array($className, $this->routeClasses) && $requestMethod == $method) {
                $className = "App\Controller\\" . $className;
                $object = new $className();

                if (method_exists($object, $classMethod)) {
                    $object->$classMethod();
                }
            } else {
                Helper::badRequest('Wrong request parameters');
            }
        } else {
            if (trim($this->request, '/')) {
                Helper::badRequest('Wrong request uri');
            } else {
                $object = new ProductController();
                $object->index();
            }
        }
    }
}