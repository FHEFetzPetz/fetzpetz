<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;
use App\FetzPetz\Services\ModelService;

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
            ['image',self::TYPE_STRING,null],
            ['cost_per_item',self::TYPE_DECIMAL,null],
            ['active',self::TYPE_INTEGER,null],
            ['search_tags',self::TYPE_OBJECT,[]]
        ];

        parent::__construct($values, $initializedFromSQL);
    }

    public function getCreatedBy(ModelService $modelService) {
        return $modelService->findOneById(User::class, $this->created_by);
    }

    public function getWishlistUsers(ModelService $modelService): array {
        $wishlistItems = $modelService->find(WishlistItem::class, ["product_id" => $this->id]);
        $users = [];

        foreach($wishlistItems as $item) {
            $users[$item->user_id] = null;
        }

        $userItems = $modelService->find(User::class, ["id" => array_keys($users)]);

        foreach($userItems as $user)
            $users[$user->id] = $user;

        return array_values($users);
    }

    public function getCategories(ModelService $modelService): array {
        $productCategories = $modelService->find(ProductCategory::class, ["product_id" => $this->id]);
        $categories = [];

        foreach($productCategories as $item) {
            $categories[$item->category_id] = null;
        }

        $categoryItems = $modelService->find(Category::class, ["id" => array_keys($categories)]);

        foreach($categoryItems as $category)
            $categories[$category->id] = $category;

        return array_values($categories);
    }
}