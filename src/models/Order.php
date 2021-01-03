<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;

class Order extends Model
{
    const TABLENAME = '`order`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['user_id',self::TYPE_INTEGER,null],
            ['payment_reference_id',self::TYPE_INTEGER,null],
            ['shipping_address_id',self::TYPE_INTEGER,null],
            ['billing_address_id',self::TYPE_INTEGER,null],
            ['order_status',self::TYPE_STRING,null],
            ['shipment_data',self::TYPE_TEXT,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }
}