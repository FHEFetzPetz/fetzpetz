<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;

class PaymentReference extends Model
{
    const TABLENAME = '`payment_reference`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['payment_method',self::TYPE_STRING,null],
            ['payment_data',self::TYPE_TEXT,null],
            ['created_at',self::TYPE_DATETIME,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }
}