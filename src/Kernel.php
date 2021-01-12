<?php

namespace App\FetzPetz;

use App\FetzPetz\Services\DatabaseService;
use App\FetzPetz\Services\LoggerService;
use App\FetzPetz\Services\ModelService;
use App\FetzPetz\Services\PaymentService;
use App\FetzPetz\Services\RequestService;
use App\FetzPetz\Services\SecurityService;

class Kernel {

    private $config;
    private $databaseService;
    private $securityService;
    private $requestService;
    private $paymentService;
    private $loggerService;
    private $modelService;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->databaseService = new DatabaseService($this);
        $this->securityService = new SecurityService($this);
        $this->requestService = new RequestService($this);
        $this->paymentService = new PaymentService($this);
        $this->loggerService = new LoggerService($this);
        $this->modelService = new ModelService($this);

        $this->databaseService->prepareHandler();
        $this->securityService->prepareService();
        $this->requestService->registerControllers();
    }

    public function getConfig(): array {
        return $this->config;
    }

    public function getDatabaseService(): DatabaseService {
        return $this->databaseService;
    }

    public function getSecurityService(): SecurityService
    {
        return $this->securityService;
    }

    public function getRequestService(): RequestService {
        return $this->requestService;
    }

    public function getPaymentService(): PaymentService {
        return $this->paymentService;
    }

    public function getLoggerService(): LoggerService {
        return $this->loggerService;
    }

    public function getModelService(): ModelService {
        return $this->modelService;
    }

    public function getDatabase(): \PDO {
        return $this->databaseService->getDatabase();
    }

    public function getAppDir(): string {
        return $this->getConfig()['appDir'];
    }

}