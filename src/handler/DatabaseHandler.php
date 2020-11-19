<?php


class DatabaseHandler extends Handler
{

    private $mysqli = null;

    private function getDatabaseConfig() : array {
        return $this->kernel->getConfig()['database'];
    }

    public function prepareHandler() {
        $this->openConnection();
    }

    public function openConnection() {
        if($this->mysqli != null) return;

        $config = $this->getDatabaseConfig();

        $mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);

        if($mysqli->connect_errno)
            die('Connection to mysql-server failed: (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);

        $this->mysqli = $mysqli;
    }

    public function closeConnection() {
        if($this->mysqli) {
            $this->mysqli->close();
            unset($this->mysqli);
        }
    }

    public function createQuery($query): bool {
        return $this->mysqli->query($query);
    }

    public function getRows($query): array {
        $output = null;

        if($result = $this->mysqli->query($query)) {
            $output = [];

            while($row = $result->fetch_row())
                $output[] = $row;
        }

        return $output;
    }

    public function getLastInsertedID(): int {
        return $this->mysqli->insert_id;
    }

}