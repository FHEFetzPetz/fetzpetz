<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;
use App\FetzPetz\Services\ModelService;

class Category extends Model
{
    const TABLENAME = '`category`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['created_by',self::TYPE_INTEGER,null],
            ['name',self::TYPE_STRING,null],
            ['description',self::TYPE_TEXT,null],
            ['image',self::TYPE_STRING,null],
            ['active',self::TYPE_INTEGER,null],
            ['parent',self::TYPE_INTEGER,null],
        ];

        parent::__construct($values, $initializedFromSQL);
    }

    public function getCreatedBy(ModelService $modelService) {
        return $modelService->findOneById(User::class, $this->created_by);
    }

    public function getChildren(ModelService $modelService) {
        return $modelService->find(Category::class, ["parent"=>$this->id]);
    }

    public function getProducts(ModelService $modelService): array {
        $productCategories = $modelService->find(ProductCategory::class, ["category_id" => $this->id]);
        $products = [];

        foreach($productCategories as $item) {
            $products[$item->product_id] = null;
        }

        $productItems = $modelService->find(Product::class, ["id" => array_keys($products)]);

        foreach($productItems as $product)
            $products[$product->id] = $product;

        return array_values($products);
    }
}