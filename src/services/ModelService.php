<?php

namespace App\FetzPetz\Services;

use App\FetzPetz\Components\Model;
use App\FetzPetz\Core\Service;

class ModelService extends Service
{

    public function find($className, array $where = [], array $select = ['*']) {
        $this->kernel->getLoggerService()->log('Called find for model ' . $className, 'debug');

        $tablename = $className::TABLENAME;
        $database = $this->kernel->getDatabase();

        $result = null;

        try {
            $sql = 'SELECT ' . join(',',$select) . ' FROM ' . $tablename;

            if(!empty($where)) {
                $whereItems = [];
                foreach($where as $key=>$value) {
                    $whereItems[] = $key . '=\'' . $value . '\'';
                }

                $sql .= ' where ' . join(' and ', $whereItems);
            }

            $result = $database->query($sql)->fetchAll();
        } catch(\PDOException $exception) {
            die('Select statement failed: ' . $exception->getMessage());
        }

        $output = [];

        foreach($result as $item) {
            $output[] = new $className($item,true);
        }

        return $output;
    }

    public function findOne($className, array $where = [], array $select = ['*'])
    {
        $this->kernel->getLoggerService()->log('Called findOne for model ' . $className, 'debug');

        $results = $this->find($className,$where,$select);
        if(sizeof($results) > 0) return $results[0];
        return null;
    }

    public function findOneById($className, int $id) {
        return $this->findOne($className, ['id' => $id]);
    }

    public function insert(Model $model, $throwError = false)
    {
        $class = get_class($model);

        $this->kernel->getLoggerService()->log('Called insert for model ' . $class, 'debug');

        $primaryKey = defined($class.'::PRIMARY_KEY') ? $class::PRIMARY_KEY : 'id';
        $tablename = $model->getTableName();
        $database = $this->kernel->getDatabase();

        $insertSchema = [];
        $insertPreparation = [];
        $insertValues = [];

        foreach($model->getSchema() as $schema) {
            $key = $schema[0];
            $value = $model->getForSQL($key);

            if($value) {
                $insertSchema[] = $key;
                $insertValues[] = $value;
                $insertPreparation[] = "?"; // used for PDO prepare with values on execute
            }
        }

        $insertId = null;

        try {
            $query = $database->prepare('INSERT INTO ' . $tablename . ' (' . join(',',$insertSchema) . ') VALUES (' . join(',',$insertPreparation) . ')');
            $database->beginTransaction();

            $query->execute($insertValues);

            $insertId = $database->lastInsertId();

            $database->commit();
        } catch(\PDOException $exception) {
            $database->rollback();
            if($throwError) throw $exception;
            die('Insert failed: ' . $exception->getMessage());
        }

        $model->__set($primaryKey, $insertId);
    }

    public function update(Model $model, $throwError = false)
    {
        $class = get_class($model);

        $this->kernel->getLoggerService()->log('Called update for model ' . $class, 'debug');

        $primaryKey = defined($class.'::PRIMARY_KEY') ? $class::PRIMARY_KEY : 'id';
        $tablename = $model->getTableName();
        $database = $this->kernel->getDatabase();

        $updateSchema = [];
        $updateValues = [];

        foreach($model->getSchema() as $schema) {
            $key = $schema[0];
            $value = $model->getForSQL($key);

            if($value && $key != $primaryKey) {
                $updateSchema[] = $key . '=?';
                $updateValues[] = $value; // used for PDO prepare with values on execute
            }
        }

        $updateValues[] = $model->getForSQL($primaryKey);

        try {
            $query = $database->prepare('UPDATE ' . $tablename . ' SET ' . join(',',$updateSchema) . ' WHERE ' . $primaryKey . '=?');
            $database->beginTransaction();
            $query->execute($updateValues);
            $database->commit();
        } catch(\PDOException $exception) {
            $database->rollback();
            if($throwError) throw $exception;
            die('Update failed: ' . $exception->getMessage());
        }
    }

    public function destroy(Model $model, $throwError = false)
    {
        $class = get_class($model);

        $this->kernel->getLoggerService()->log('Called destroy for model ' . $class, 'debug');

        $primaryKey = defined($class.'::PRIMARY_KEY') ? $class::PRIMARY_KEY : 'id';
        $tablename = $model->getTableName();
        $database = $this->kernel->getDatabase();

        try {
            $query = $database->prepare('DELETE FROM ' . $tablename . ' WHERE ' . $primaryKey . '=?');

            $database->beginTransaction();
            $query->execute([$model->getForSQL($primaryKey)]);
            $database->commit();
        } catch(\PDOException $exception) {
            $database->rollback();
            if($throwError) throw $exception;
            die('Deletion failed: ' . $exception->getMessage());
        }

        $model->__unset($primaryKey);
    }
}