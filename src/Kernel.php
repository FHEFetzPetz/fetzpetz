<?php

namespace App\FetzPetz;

use App\FetzPetz\Services\DatabaseService;
use App\FetzPetz\Services\LoggerService;
use App\FetzPetz\Services\ModelService;
use App\FetzPetz\Services\NotificationService;
use App\FetzPetz\Services\ShopService;
use App\FetzPetz\Services\RequestService;
use App\FetzPetz\Services\SecurityService;

class Kernel {

    private $config;
    private $databaseService;
    private $securityService;
    private $requestService;
    private $shopService;
    private $loggerService;
    private $modelService;
    private $notificationService;

    /**
     * Kernel constructor.
     * Initializing the Kernel and services
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->databaseService = new DatabaseService($this);
        $this->securityService = new SecurityService($this);
        $this->requestService = new RequestService($this);
        $this->shopService = new ShopService($this);
        $this->loggerService = new LoggerService($this);
        $this->modelService = new ModelService($this);
        $this->notificationService = new NotificationService($this);

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

    public function getShopService(): ShopService {
        return $this->shopService;
    }

    public function getLoggerService(): LoggerService {
        return $this->loggerService;
    }

    public function getModelService(): ModelService {
        return $this->modelService;
    }

    public function getNotificationService(): NotificationService {
        return $this->notificationService;
    }

    public function getDatabase(): \PDO {
        return $this->databaseService->getDatabase();
    }

    public function getAppDir(): string {
        return $this->getConfig()['appDir'];
    }

}