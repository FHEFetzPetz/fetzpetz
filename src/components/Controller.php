<?php

namespace App\FetzPetz\Components;

use App\FetzPetz\Core\Service;
use App\FetzPetz\Model\User;

/**
 * Class Controller for endpoints (GET, POST, ...)
 * @package App\FetzPetz\Components
 */
class Controller extends Service
{

    private $view = null;
    private $template = "base";
    private $variables = [];
    private $extraHeaderFields = [];
    private $canRender = true;

    /**
     * method which will be implemented in inherited classes
     * @return array
     */
    public function shareRoutes(): array {
        return [];
    }

    public function getConfig() : array {
        return $this->kernel->getConfig();
    }

    private function getRoutingConfig() : array {
        return $this->getConfig()['routing'];
    }

    /**
     * adds parameter for direct access inside rendered view (extraction)
     * @param string $key
     * @param $value
     */
    public function setParameter(string $key, $value) {
        $this->variables[$key] = $value;
    }

    public function getParameters() : array {
        return $this->variables;
    }

    /**
     * changes the template in which the view will be rendered
     * @param string $template
     */
    public function setTemplate(string $template) {
        $this->template = $template;
    }

    public function getTemplate() {
        return $this->template;
    }

    /**
     * changes the view which will be rendered in side the template
     * @param string $view
     */
    public function setView(string $view) {
        $this->view = $view;
    }

    public function getView() : ?string {
        return $this->view;
    }

    /**
     * adds header fields which will be rendered inside the templates header-section
     * @param $extraFields
     */
    public function addExtraHeaderFields($extraFields) {
        $this->extraHeaderFields = array_merge($this->extraHeaderFields, $extraFields);
    }

    public function getExtraHeaderFields() : array {
        return $this->extraHeaderFields;
    }

    /**
     * renders the template containing views and components
     * if it is allowed to render. If the template file is
     * not available the page will not be rendered.
     *
     * Parameters from setParameters will be extracted
     */
    public function renderTemplate() {
        if(!$this->canRender) return;

        $templatesDirectory = $this->kernel->getAppDir() . '/' . $this->getRoutingConfig()['viewDirectory'] . '/templates';
        $templatePath = $templatesDirectory . '/' . $this->template . '.php';

        if(is_file($templatePath) && pathinfo($templatePath, PATHINFO_EXTENSION) == 'php') {

            $this->variables["extraHeaderFields"] = $this->extraHeaderFields;
            extract($this->variables, EXTR_PREFIX_SAME, 'param');

            require_once($templatePath);
        } else {
            die('Template file not found');
        }
    }

    /**
     * renders the view inside the template which the user set
     * in the endpoint. If the view does not exist the page
     * will not be rendered.
     *
     * Parameters from setParameters will be extracted
     */
    protected function renderView() {
        if(!$this->canRender) return;

        if($this->view == null)
            die("No view path provided");

        $viewsDirectory = $this->kernel->getAppDir() . '/' . $this->getRoutingConfig()['viewDirectory'];
        $filePath = $viewsDirectory . '/' . $this->view;

        if(is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) == 'php') {

            extract($this->variables, EXTR_PREFIX_SAME, 'param');

            require_once($filePath);
        } else {
            die('File to render not found');
        }
    }

    /**
     * renders a component from a given path with extra variables.
     * If the component file does not exist it will not be rendered.
     *
     * Existing parameters will be merged with extra variables
     *
     * @param $path
     * @param array $variables
     */
    protected function renderComponent($path, $variables = []) {
        if(!$this->canRender) return;

        $variables = array_merge($variables, $this->getParameters());

        $viewsDirectory = $this->kernel->getAppDir() . '/' . $this->getRoutingConfig()['viewDirectory'];
        $componentPath = $viewsDirectory . '/' . $path;

        if(is_file($componentPath) && pathinfo($componentPath, PATHINFO_EXTENSION) == 'php') {

            extract($variables, EXTR_PREFIX_SAME, 'param');

            require($componentPath);
        } else {
            die('Component to render not found');
        }
    }

    /**
     * Returns the absolute path of the given url with
     * schema, url and if given subDirectory
     *
     * Based on htaccessRouting Config the url will be
     * generated as a query parameter or normal url
     *
     * @param $url
     * @return string
     */
    public function getAbsolutePath($url) {
        $config = $this->kernel->getConfig();

        $baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . $this->kernel->getConfig()["subDirectory"];

        if($this->kernel->getConfig()["htaccessRouting"])
            return $baseURL .= $url;
        else
            return $baseURL .= "?page=$url";
    }

    /**
     * Returns the relative path of the given url
     *
     * Based on htaccessRouting Config the url will be
     * generated as a query parameter or normal url
     *
     * @param $url
     * @return string
     */
    public function getPath($url) {
        if($this->kernel->getConfig()["htaccessRouting"])
            return $url;
        else
            return "/?page=$url";
    }

    public function getUser(): ?User {
        return $this->kernel->getSecurityService()->getUser();
    }

    public function isAuthenticated(): bool {
        return $this->kernel->getSecurityService()->isAuthenticated();
    }

    /**
     * Redirects the user to a local path and
     * cancels the template, view, component rendering
     *
     * @param $url
     */
    public function redirectTo($url) {
        $this->canRender = false;
        header("Location: " . $this->getPath($url));
    }

}