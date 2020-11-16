<?php

require_once "handler/Handler.php";
require_once "handler/RequestHandler.php";
require_once "handler/Logger.php";

class Kernel {

    private $config;
    private $requestHandler;
    private $logger;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->requestHandler = new RequestHandler($this);
        $this->logger = new Logger($this);

        $this->requestHandler->registerControllers();
    }

    public function getConfig(): array {
        return $this->config;
    }

    public function getRequestHandler(): RequestHandler {
        return $this->requestHandler;
    }

    public function getLogger(): Logger {
        return $this->logger;
    }

    public function getAppDir(): string {
        return $this->getConfig()["appDir"];
    }

}