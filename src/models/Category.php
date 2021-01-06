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
        ];

        parent::__construct($values, $initializedFromSQL);
    }

    public function getCreatedBy(ModelService $modelService) {
        return $modelService->findOneById(User::class, $this->__get("created_by"));
    }
}