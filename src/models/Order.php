<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;
use App\FetzPetz\Services\ModelService;

class Order extends Model
{
    const TABLENAME = '`order_object`';
    const PRIMARY_KEY = 'id';

    public function __construct($values, $initializedFromSQL = false) {
        $this->schema = [
            ['id',self::TYPE_INTEGER,null],
            ['user_id',self::TYPE_INTEGER,null],
            ['payment_reference_id',self::TYPE_INTEGER,null],
            ['shipping_address_id',self::TYPE_INTEGER,null],
            ['billing_address_id',self::TYPE_INTEGER,null],
            ['order_status',self::TYPE_STRING,null],
            ['shipment_data',self::TYPE_OBJECT,null]
        ];

        parent::__construct($values, $initializedFromSQL);
    }

    public function getUserID(ModelService $modelService) {
        return $modelService->findOneById(User::class, $this->user_id);
    }

    public function getPaymentReferenceID(ModelService $modelService) {
        return $modelService->findOneById(PaymentReference::class, $this->payment_reference_id);
    }

    public function getShippingAddressId(ModelService $modelService) {
        return $modelService->findOneById(Address::class, $this->shipping_address_id);
    }

    public function getBillingAddressId(ModelService $modelService) {
        return $modelService->findOneById(Address::class, $this->billing_address_id);
    }
}