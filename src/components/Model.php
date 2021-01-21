<?php

namespace App\FetzPetz\Components;

/**
 * Class Model for database tables
 * @package App\FetzPetz\Components
 */
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

    /**
     * Model constructor which places the given values in the private values array.
     *
     * @param $values
     * @param false $initializedFromSQL if true values will be converted to php-compatible datatypes (e.g. DateTime)
     */
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

    /**
     * Returns the schema item by the given key if it exists
     * @param $key
     * @return mixed|null
     */
    private function getSchemaItem($key) {
        foreach($this->schema as $item) {
            if($item[0] == $key) return $item;
        }

        return null;
    }

    private function inSchema($key) {
        return $this->getSchemaItem($key) != null;
    }

    /**
     * Updates a value if the schema exists
     *
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        if(!$this->inSchema($key)) return;
        $this->values[$key] = $value;
    }

    /**
     * Returns a value if the schema exists
     * If no value is set it will returns the
     * default value (if it exists either)
     *
     * @param $key
     * @return mixed|null
     */
    public function __get($key)
    {
        $schemaItem = $this->getSchemaItem($key);
        if($schemaItem == null) return null;

        return $this->values[$key] ?? $schemaItem[2];
    }

    /**
     * Removes a value if the schema exists
     *
     * @param $key
     */
    public function __unset($key)
    {
        if(!$this->inSchema($key)) return;
        unset($this->values[$key]);
    }

    /**
     * Returns a value converted as a mysql ready string
     *
     * @param $key
     * @return int|mixed|null
     */
    public function getForSQL($key) {
        $schemaItem = $this->getSchemaItem($key);
        if($schemaItem == null) return null;

        $type = $schemaItem[1];
        $value = $this->values[$key] ?? $schemaItem[2];

        if(!$value) return null;

        switch($type) {
            case self::TYPE_DATE: return $value->format('Y-m-d H:i:s');
            case self::TYPE_DATETIME: return $value->format('Y-m-d H:i:s');
            case self::TYPE_BOOL: return $value ? 1 : 0;
            default: return $value;
        }
    }

    /**
     * Returns a datatype converted from mysql into php compatible types
     *
     * @param $key
     * @param $value
     * @return bool|\DateTime|null
     * @throws \Exception
     */
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