<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;

class Address extends Model
{
    const TABLENAME = '`address`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['firstname',self::TYPE_STRING,null],
            ['lastname',self::TYPE_STRING,null],
            ['street',self::TYPE_STRING,null],
            ['zip',self::TYPE_STRING,null],
            ['city',self::TYPE_STRING,null],
            ['state',self::TYPE_STRING,null],
            ['country',self::TYPE_STRING,null],
            ['phoneNumber',self::TYPE_STRING,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }
}