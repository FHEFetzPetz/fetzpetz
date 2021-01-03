<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;

class Address extends Model
{
    const TABLENAME = '`administrationAccess`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['user_id',self::TYPE_INTEGER,null],
            ['created_by',self::TYPE_INTEGER,null],
            ['active',self::TYPE_INTEGER,null],
            ['created_at',self::TYPE_DATETIME,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }
}