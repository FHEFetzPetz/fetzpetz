<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;

class OrderItem extends Model
{
    const TABLENAME = '`orderItem`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['order_id',self::TYPE_INTEGER,null],
            ['product_id',self::TYPE_INTEGER,null],
            ['amount',self::TYPE_INTEGER,null],
            ['cost_per_item',self::TYPE_DECIMAL,null],
            ['item_data',self::TYPE_TEXT,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }
}