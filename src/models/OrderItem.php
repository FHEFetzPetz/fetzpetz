<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;
use App\FetzPetz\Services\ModelService;

class OrderItem extends Model
{
    const TABLENAME = '`order_item`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['order_id',self::TYPE_INTEGER,null],
            ['product_id',self::TYPE_INTEGER,null],
            ['amount',self::TYPE_INTEGER,null],
            ['cost_per_item',self::TYPE_DECIMAL,null],
            ['item_data',self::TYPE_OBJECT,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }

    public function getOrder(ModelService $modelService) {
        return $modelService->findOneById(Order::class, $this->order_id);
    }

    public function getProduct(ModelService $modelService) {
        return $modelService->findOneById(Product::class, $this->product_id);
    }
}