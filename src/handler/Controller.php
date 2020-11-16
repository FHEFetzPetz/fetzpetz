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

}