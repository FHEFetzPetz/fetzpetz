<?php

require_once "handler/Handler.php";
require_once "handler/DatabaseHandler.php";
require_once "handler/RequestHandler.php";
require_once "handler/PaymentHandler.php";
require_once "handler/Logger.php";

class Kernel {

    private $config;
    private $databaseHandler;
    private $requestHandler;
    private $paymentHandler;
    private $logger;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->databaseHandler = new DatabaseHandler($this);
        $this->requestHandler = new RequestHandler($this);
        $this->paymentHandler = new PaymentHandler($this);
        $this->logger = new Logger($this);

        $this->databaseHandler->prepareHandler();
        $this->requestHandler->registerControllers();
    }

    public function getConfig(): array {
        return $this->config;
    }

    public function getDatabaseHandler(): DatabaseHandler {
        return $this->databaseHandler;
    }

    public function getRequestHandler(): RequestHandler {
        return $this->requestHandler;
    }

    public function getPaymentHandler(): PaymentHandler {
        return $this->paymentHandler;
    }

    public function getLogger(): Logger {
        return $this->logger;
    }

    public function getAppDir(): string {
        return $this->getConfig()['appDir'];
    }

}