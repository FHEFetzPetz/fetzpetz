<?php

require_once "Controller.php";

class RequestHandler extends Handler
{

    private $requestURI;
    private $baseURL;

    private $controllerClasses = [];
    private $routes = [];

    public function handleRequest() {

        $this->requestURI = $this->kernel->getConfig()["htaccessRouting"] ? $_SERVER['REQUEST_URI'] : urldecode($_GET["page"] ?? urlencode("/"));
        $this->baseURL = $this->getBaseURL();

        $this->kernel->getLogger()->log('Handling request for URI: ' . $this->getRequestURI(), 'debug');

        $routeFound = false;

        foreach($this->routes as $route => $data) {
            if($route == $this->baseURL) {

                $this->kernel->getLogger()->log('Found route for URI: ' . $this->getRequestURI() . ' (' . $data[0] . '->' . $data[1] . ')', 'debug');

                $this->controllerClasses[$data[0]]->{$data[1]}();
                $routeFound = true;
            }
        }

        if(!$routeFound) {
            $this->kernel->getLogger()->log('Found no route for URI: ' . $this->getRequestURI(), 'debug');

            echo '404 - Route not found';
        }
    }

    public function getBaseURL() {
        $withoutQuery = explode('?', $this->requestURI)[0];
        return explode('#', $withoutQuery)[0];
    }

    public function getRequestURI(): string {
        return $this->requestURI;
    }

    public function registerControllers() {
        $controllerDirectory = $this->kernel->getAppDir() . '/src/controller';

        foreach(scandir($controllerDirectory) as $fileName) {
            $path = $controllerDirectory . '/' . $fileName;
            if(is_file($path) && pathinfo($path, PATHINFO_EXTENSION) == 'php') {
                require_once($path);

                $className = explode('.',$fileName)[0];

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