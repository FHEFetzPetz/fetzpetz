<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;
use App\FetzPetz\Services\ModelService;

class Address extends Model
{
    const TABLENAME = '`administration_access`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['user_id',self::TYPE_INTEGER,null],
            ['created_by',self::TYPE_INTEGER,null],
            ['active',self::TYPE_INTEGER,null],
            ['created_at',self::TYPE_DATETIME,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }

    public function getUserID(ModelService $modelService) {
        return $modelService->findOneById(User::class, $this->__get("user_id"));
    }

    public function getCreatedByID(ModelService $modelService) {
        return $modelService->findOneById(User::class, $this->__get("created_by"));
    }
}