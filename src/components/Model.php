<?php

namespace App\FetzPetz\Components;

class Model
{

    const TYPE_STRING   = 'string';
    const TYPE_INTEGER  = 'integer';
    const TYPE_DECIMAL  = 'decimal';
    const TYPE_DATE     = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_JSON     = 'json';
    const TYPE_BOOL     = 'bool';
    const TYPE_TEXT     = 'text';

    protected $schema = [];
    private $values = [];

    public function __construct($values, $initializedFromSQL = false) {
        foreach($values as $key=>$value)
            if($this->inSchema($key))
                $this->values[$key] = $initializedFromSQL ? $this->getFromSQL($key,$value) : $value;
    }

    public static function getTableName() {
        $class = get_called_class();
        if(defined($class.'::TABLENAME'))
            return $class::TABLENAME;
        return null;
    }

    public function getSchema() {
        return $this->schema;
    }

    public function getValues() {
        return $this->values;
    }

    private function getSchemaItem($key) {
        foreach($this->schema as $item) {
            if($item[0] == $key) return $item;
        }

        return null;
    }

    private function inSchema($key) {
        return $this->getSchemaItem($key) != null;
    }

    public function __set($key, $value)
    {
        if(!$this->inSchema($key)) return;
        $this->values[$key] = $value;
    }

    public function __get($key)
    {
        $schemaItem = $this->getSchemaItem($key);
        if($schemaItem == null) return null;

        return $this->values[$key] ?? $schemaItem[2];
    }

    public function __unset($key)
    {
        if(!$this->inSchema($key)) return;
        unset($this->values[$key]);
    }

    public function getForSQL($key) {
        $schemaItem = $this->getSchemaItem($key);
        if($schemaItem == null) return null;

        $type = $schemaItem[1];
        $value = $this->values[$key] ?? $schemaItem[2];

        if(!$value) return null;

        switch($type) {
            case self::TYPE_DATE: return $value->format('Y-m-d H:i:s');
            case self::TYPE_BOOL: return $value ? 1 : 0;
            default: return $value;
        }
    }

    public function getFromSQL($key, $value) {
        $schemaItem = $this->getSchemaItem($key);
        if($schemaItem == null) return null;

        $type = $schemaItem[1];

        if(!$value) return null;

        switch($type) {
            case self::TYPE_DATE: return new \DateTime($value);
            case self::TYPE_BOOL: return $value == 1;
            default: return $value;
        }
    }

    public function __destruct()
    {}
}