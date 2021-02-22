<?php

namespace App\FetzPetz\Model;

use App\FetzPetz\Components\Model;
use App\FetzPetz\Services\ModelService;

class Order extends Model
{
	const TABLENAME = '`order_object`';
	const PRIMARY_KEY = 'id';

	public function __construct($values, $initializedFromSQL = false)
	{
		$this->schema = [
			['id', self::TYPE_INTEGER, null],
			['user_id', self::TYPE_INTEGER, null],
			['payment_reference_id', self::TYPE_INTEGER, null],
			['shipping_address_id', self::TYPE_INTEGER, null],
			['billing_address_id', self::TYPE_INTEGER, null],
			['order_status', self::TYPE_STRING, null],
			['shipment_data', self::TYPE_OBJECT, null],
			['created_at', self::TYPE_DATETIME, null]
		];

		parent::__construct($values, $initializedFromSQL);
	}

	public function getUser(ModelService $modelService)
	{
		return $modelService->findOneById(User::class, $this->user_id);
	}

	public function getPaymentReference(ModelService $modelService)
	{
		return $modelService->findOneById(PaymentReference::class, $this->payment_reference_id);
	}

	public function getShippingAddress(ModelService $modelService)
	{
		return $modelService->findOneById(Address::class, $this->shipping_address_id);
	}

	public function getBillingAddress(ModelService $modelService)
	{
		return $modelService->findOneById(Address::class, $this->billing_address_id);
	}

	/**
	 * returns products which are linked in the order-item class (many to one)
	 *
	 * @param ModelService $modelService
	 * @return array
	 */
	public function getProducts(ModelService $modelService)
	{
		$orderItems = $modelService->find(OrderItem::class, ['order_id' => $this->id]);
		$productIds = [];

		foreach ($orderItems as $item)
			$productIds[$item->product_id] = $item;

		$products = $modelService->find(Product::class, ['id' => array_keys($productIds)]);

		foreach ($products as $product)
			$productIds[$product->id]->product = $product;

		return array_values($productIds);
	}

	/**
	 * returns the total of all order-items
	 *
	 * @param ModelService $modelService
	 * @return void
	 */
	public function getTotal(ModelService $modelService)
	{
		$orderItems = $modelService->find(OrderItem::class, ['order_id' => $this->id]);
		$total = 0;

		foreach ($orderItems as $item)
			$total += $item->cost_per_item * $item->amount;

		return $total;
	}
}
