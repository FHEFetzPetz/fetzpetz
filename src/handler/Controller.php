<?php

class Controller extends Handler
{

    public function shareRoutes(): array {
        return [];
    }

    private function getRoutingConfig() : array {
        return $this->kernel->getConfig()['routing'];
    }

    protected function renderView(string $path) {
        $viewsDirectory = $this->kernel->getAppDir() . '/' . $this->getRoutingConfig()['viewDirectory'];
        $filePath = $viewsDirectory . '/' . $path;

        if(is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) == 'php') {
            require_once($filePath);
        } else {
            echo 'File to render not found';
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

}