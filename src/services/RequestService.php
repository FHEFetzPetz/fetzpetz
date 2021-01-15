<?php

namespace App\FetzPetz\Services;

use App\FetzPetz\Components\Model;
use App\FetzPetz\Core\Service;

class RequestService extends Service
{

    private $requestURI;
    private $baseURL;

    private $controllerClasses = [];
    private $routes = [];

    /**
     * Handles the request and selecting the matching route
     * if exists, otherwise returning a 404-page (if it exists)
     */
    public function handleRequest() {

        // getting the requestURI by query-parameter or request-url based on htaccess-routing configuration
        $this->requestURI = $this->kernel->getConfig()["htaccessRouting"] ? $_SERVER['REQUEST_URI'] : urldecode($_GET["page"] ?? urlencode("/"));
        $this->baseURL = $this->parseBaseURL();

        $this->kernel->getLoggerService()->log('Handling request for URI: ' . $this->getRequestURI(), 'debug');

        $routeFound = false;

        $fallback404Route = null;

        // looking for a matching route
        foreach($this->routes as $route => $data) {
            if($route == $this->baseURL) {

                $this->kernel->getLoggerService()->log('Found route for URI: ' . $this->getRequestURI() . ' (' . $data[0] . '->' . $data[1] . ')', 'debug');

                // calling the found method inside the matching controller
                $this->controllerClasses[$data[0]]->{$data[1]}();

                // renders the template
                $this->controllerClasses[$data[0]]->renderTemplate();
                $routeFound = true;
            } else if($fallback404Route == null && $route == "404")
                $fallback404Route = $data;
        }

        if(!$routeFound && $fallback404Route) {

            // if no route was found but a custom 404 route was specified
            $this->controllerClasses[$fallback404Route[0]]->{$fallback404Route[1]}();
            $this->controllerClasses[$fallback404Route[0]]->renderTemplate();
        } else if(!$routeFound) {

            // if no route way found and no 404 route was specified it will log the error and returns it
            $this->kernel->getLoggerService()->log('Found no route for URI: ' . $this->getRequestURI(), 'debug');

            echo '404 - Route not found';
        }
    }

    /**
     * parses the url by removing query parameters and fragment
     * @return string
     */
    private function parseBaseURL(): string {
        $withoutQuery = explode('?', $this->requestURI)[0];
        return explode('#', $withoutQuery)[0];
    }

    public function getBaseURL(): string {
        return $this->baseURL;
    }

    public function getRequestURI(): string {
        return $this->requestURI;
    }

    /**
     * Scanning for controllers inside the controller-directory
     * and registering the route-links
     */
    public function registerControllers() {
        $controllerDirectory = $this->kernel->getAppDir() . '/src/controller';

        foreach(scandir($controllerDirectory) as $fileName) {
            $path = $controllerDirectory . '/' . $fileName;

            if(is_file($path) && pathinfo($path, PATHINFO_EXTENSION) == 'php') {
                $className = "\\App\\FetzPetz\\Controller\\" . explode('.',$fileName)[0];

                if(class_exists($className)) {
                    $controllerClass = new $className($this->kernel);
                    $sharedRoutes = $controllerClass->shareRoutes();

                    $this->controllerClasses[$className] = $controllerClass;

                    foreach($sharedRoutes as $route => $method) {
                        $this->routes[$route] = [$className, $method];
                    }
                }
            }
        }
    }

}