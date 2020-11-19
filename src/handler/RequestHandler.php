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
        $this->baseURL = $this->parseBaseURL();

        $this->kernel->getLogger()->log('Handling request for URI: ' . $this->getRequestURI(), 'debug');

        $routeFound = false;

        $fallback404Route = null;

        foreach($this->routes as $route => $data) {
            if($route == $this->baseURL) {

                $this->kernel->getLogger()->log('Found route for URI: ' . $this->getRequestURI() . ' (' . $data[0] . '->' . $data[1] . ')', 'debug');

                $this->controllerClasses[$data[0]]->{$data[1]}();
                $routeFound = true;
            } else if($fallback404Route == null && $route == "404")
                $fallback404Route = $data;
        }

        if(!$routeFound && $fallback404Route) {
            $this->controllerClasses[$fallback404Route[0]]->{$fallback404Route[1]}();
        } else if(!$routeFound) {
            $this->kernel->getLogger()->log('Found no route for URI: ' . $this->getRequestURI(), 'debug');

            echo '404 - Route not found';
        }
    }

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