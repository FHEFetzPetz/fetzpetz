<?php

namespace App\FetzPetz\Components;

use App\FetzPetz\Core\Service;
use App\FetzPetz\Model\User;

class Controller extends Service
{

    private $view = null;
    private $template = "base";
    private $variables = [];
    private $extraHeaderFields = [];
    private $canRender = true;

    public function shareRoutes(): array {
        return [];
    }

    public function getConfig() : array {
        return $this->kernel->getConfig();
    }

    private function getRoutingConfig() : array {
        return $this->getConfig()['routing'];
    }

    public function setParameter(string $key, $value) {
        $this->variables[$key] = $value;
    }

    public function getParameters() : array {
        return $this->variables;
    }

    public function setTemplate(string $template) {
        $this->template = $template;
    }

    public function getTemplate() {
        return $this->template;
    }

    public function setView(string $view) {
        $this->view = $view;
    }

    public function getView() : ?string {
        return $this->view;
    }

    public function addExtraHeaderFields($extraFields) {
        $this->extraHeaderFields = array_merge($this->extraHeaderFields, $extraFields);
    }

    public function getExtraHeaderFields() : array {
        return $this->extraHeaderFields;
    }

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

    public function getAbsolutePath($url) {
        $config = $this->kernel->getConfig();

        $baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . $this->kernel->getConfig()["subDirectory"];

        if($this->kernel->getConfig()["htaccessRouting"])
            return $baseURL .= $url;
        else
            return $baseURL .= "?page=$url";
    }

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

    public function redirectTo($url) {
        $this->canRender = false;
        header("Location: " . $this->getPath($url));
    }

}