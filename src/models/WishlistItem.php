<?php


namespace App\FetzPetz\Model;


use App\FetzPetz\Components\Model;
use App\FetzPetz\Services\ModelService;

class WishlistItem extends Model
{
    const TABLENAME = '`wishlist_item`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['product_id',self::TYPE_INTEGER,null],
            ['user_id',self::TYPE_INTEGER,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }

    public function getProduct(ModelService $modelService) {
        if(!isset($this->product))  $this->product = $modelService->findOneById(Product::class, $this->product_id);
        return $this->product;
    }

    public function getUser(ModelService $modelService) {
        if(!isset($this->user))  $this->user = $modelService->findOneById(User::class, $this->user_id);
        return $this->user;
    }

    public function getAllRelations(ModelService $modelService) {
        $this->getProduct($modelService);
        $this->getUser($modelService);
    }
}