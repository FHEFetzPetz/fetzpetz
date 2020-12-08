<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;

class User extends Model
{
    const TABLENAME = '`user`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['firstname',self::TYPE_STRING,null],
            ['lastname',self::TYPE_STRING,null],
            ['birthday',self::TYPE_DATE,null],
            ['password',self::TYPE_STRING,null],
            ['email',self::TYPE_STRING,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }
}