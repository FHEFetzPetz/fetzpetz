<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;

class Product extends Model
{
    const TABLENAME = '`product`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['created_by',self::TYPE_INTEGER,null],
            ['name',self::TYPE_STRING,null],
            ['description',self::TYPE_TEXT,null],
            ['product_images',self::TYPE_TEXT,null],
            ['extra_attributes',self::TYPE_TEXT,null],
            ['cost_per_item',self::TYPE_DECIMAL,null],
            ['availability',self::TYPE_INTEGER,null],
            ['active',self::TYPE_INTEGER,null],
            ['search_tags',self::TYPE_TEXT,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }
}