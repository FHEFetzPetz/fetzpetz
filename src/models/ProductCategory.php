<?php


namespace App\FetzPetz\Model;


use App\FetzPetz\Components\Model;
use App\FetzPetz\Services\ModelService;

class ProductCategory extends Model
{
    const TABLENAME = '`product_category`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['product_id',self::TYPE_INTEGER,null],
            ['category_id',self::TYPE_INTEGER,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }

    public function getProduct(ModelService $modelService) {
        if(!isset($this->product))  $this->product = $modelService->findOneById(Product::class, $this->product_id);
        return $this->product;
    }

    public function getCategory(ModelService $modelService) {
        if(!isset($this->category))  $this->category = $modelService->findOneById(Category::class, $this->category_id);
        return $this->category;
    }

    public function getAllRelations(ModelService $modelService) {
        $this->getProduct($modelService);
        $this->getCategory($modelService);
    }
}