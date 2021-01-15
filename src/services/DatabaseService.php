<?php

namespace App\FetzPetz\Services;

use App\FetzPetz\Core\Service;

class DatabaseService extends Service
{

    private $database = null;

    private function getDatabaseConfig() : array {
        return $this->kernel->getConfig()['database'];
    }

    public function prepareHandler() {
        $this->openConnection();
    }

    /**
     * Opens the database connection with configuration values
     */
    public function openConnection() {
        if($this->database != null) return;

        $config = $this->getDatabaseConfig();

        try {
            $this->database = new \PDO(
                'mysql:dbname=' . $config['database'] . ';host=' . $config['host'] . ':' . $config['port'],
                $config['username'],
                $config['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
        } catch(\PDOException $exception) {
            die('Connection to mysql-server failed: ' . $exception->getMessage());
        }
    }

    public function closeConnection() {
        if($this->database) {
            unset($this->database);
        }
    }

    public function getDatabase() {
        return $this->database;
    }
}