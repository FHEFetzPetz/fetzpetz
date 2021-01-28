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
            $match = $this->matchesRoute($route);
            if($match !== false) {

                $this->kernel->getLoggerService()->log('Found route for URI: ' . $this->getRequestURI() . ' (' . $data[0] . '->' . $data[1] . ')', 'debug');

                // calling the found method inside the matching controller
                call_user_func_array(array($this->controllerClasses[$data[0]],$data[1]), $match);

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
     * Splitting the url in pieces and search for variables (if required)
     * returns false, an empty array or arrays with matching variables and values
     *
     * @param $route
     * @return array|false
     */
    private function matchesRoute($route) {
        $baseUrl = $this->getBaseURL();
        $variables = [];
        $exportedVariables = [];

        $matchString = preg_quote($route, '/');

        // searching for variable matches in controller route
        // example: /cart/{id}/{action} will be split into ['{id}','{action}']
        preg_match_all('/{[a-zA-Z0-9]+}/', $route, $variables);

        if(sizeof($variables) > 0 && sizeof($variables[0]) > 0) {

            //looping through found variables
            foreach($variables[0] as $variable) {

                //replacing variable keys in the url to match with regular expressions for finding matches
                $matchString = str_replace(preg_quote($variable, '/'), '[a-zA-Z0-9\-\_]+', $matchString);

                //adding variable names without {} into an temporary array
                $variableName = preg_replace('/[{|}]/', '', $variable);
                $exportedVariables[$variableName] = "";
            }
        }

        /**
         * determining if the url matches the reg. controller route
         *
         * @example user opens /cart/add/5
         * Route existing with name /cart/{action}/{id}
         * $matchString will be /^\/cart\/[a-zA-Z0-9\-\_]+\/[a-zA-Z0-9\-\_]+\/?$/
         */
        $isMatch = preg_match("/^" . $matchString . "\/?$/", $baseUrl);

        // if no match is found function will return false (as usual)
        if($isMatch != 1) return false;

        // if any variables need to be exported
        if(sizeof($exportedVariables) > 0) {

            // splitting the controller route into pieces by the variable with {}
            $parts = preg_split('/{[a-zA-Z0-9]+}/', $route);

            $index = 0;
            $remainingString = preg_replace('/' . preg_quote($parts[0], '/') . '/', '', $baseUrl, 1);

            // looping through every variable again
            foreach($exportedVariables as $variable => $key) {

                // removing the unnecessary parts of the remaining user url to fetch the necessary variable
                $value = strlen($parts[$index + 1]) > 0 ? substr($remainingString, 0, strpos($remainingString, $parts[$index + 1])) : $remainingString;

                // shrinking the remaining string for other variables with the found variable and remaining parts
                $remainingString = preg_replace('/' . preg_quote($value . $parts[$index + 1], '/') . '/', '', $remainingString, 1);

                $index++;

                $exportedVariables[$variable] = $value;
            }
        }

        return $exportedVariables;
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

    public function getRoutes() {
        return $this->routes;
    }

}