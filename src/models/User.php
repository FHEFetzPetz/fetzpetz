<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;
use App\FetzPetz\Services\ModelService;

class User extends Model
{
    const TABLENAME = '`user`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['firstname',self::TYPE_STRING,null],
            ['lastname',self::TYPE_STRING,null],
            ['password_hash',self::TYPE_STRING,null],
            ['email',self::TYPE_STRING,null],
            ['email_verified',self::TYPE_INTEGER,null],
            ['email_verification_hash',self::TYPE_STRING,null],
            ['active',self::TYPE_INTEGER,null],
            ['created_at',self::TYPE_DATETIME,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }

    public function getWishlistProducts(ModelService $modelService): array {
        $wishlistItems = $modelService->find(WishlistItem::class, ["user_id" => $this->id]);
        $products = [];

        foreach($wishlistItems as $item) {
            $products[$item->product_id] = null;
        }

        $productItems = $modelService->find(Product::class, ["id" => array_keys($products)]);

        foreach($productItems as $product)
            $products[$product->id] = $product;

        return array_values($products);
    }
}