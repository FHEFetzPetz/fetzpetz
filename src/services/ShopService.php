<?php

namespace App\FetzPetz\Services;

use App\FetzPetz\Core\Service;
use App\FetzPetz\Model\Product;

class ShopService extends Service
{

    /**
     * Returns an array with cart items stored in the session with fetched products
     * @return array
     */
    public function getCart(): array
    {
        $cartItems = $_SESSION["cart"] ?? [];
        $output = [];
        $productEntries = $this->kernel->getModelService()->find(Product::class, ["id" => array_keys($cartItems)]);

        foreach ($productEntries as $product) {
            $cartItem = $cartItems[$product->__get("id")];
            $total = $product->__get("cost_per_item") * $cartItem["quantity"];

            $output[] = array_merge(["product" => $product, "total" => $total], $cartItem);
        }

        return $output;
    }

    public function getProductInCart(Product $product): ?array
    {
        $cartItems = $_SESSION["cart"] ?? [];
        $id = $product->__get("id");

        if (isset($cartItems[$id])) {
            $cartItem = $cartItems[$id];
            $total = $product->__get("cost_per_item") * $cartItem["quantity"];

            return array_merge(["product_id" => intval($id), "total" => $total], $cartItem);
        }

        return null;
    }

    /**
     * Adds a product to the cart or increases the existing products quantity
     * @param Product $product
     * @param int $quantity
     */
    public function addToCart(Product $product, int $quantity)
    {
        $cartItems = $_SESSION["cart"] ?? [];

        if (isset($cartItems[$product->__get("id")]))
            $cartItems[$product->__get("id")] = ["last_updated" => new \DateTime(), "quantity" => $quantity + $cartItems[$product->__get('id')]['quantity']];
        else
            $cartItems[$product->__get("id")] = ["last_updated" => new \DateTime(), "quantity" => $quantity];

        $_SESSION["cart"] = $cartItems;
    }

    /**
     * Removes the product from the cart if existing
     * @param Product $product
     */
    public function removeFromCart(Product $product)
    {
        $cartItems = $_SESSION["cart"] ?? [];

        if (isset($cartItems[$product->__get("id")]))
            unset($cartItems[$product->__get("id")]);

        $_SESSION["cart"] = $cartItems;
    }

    /**
     * Changes the quantity of a product if existing
     * @param Product $product
     * @param int $quantity
     */
    public function changeQuantity(Product $product, int $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($product);
            return;
        }
        $cartItems = $_SESSION["cart"] ?? [];

        if (isset($cartItems[$product->__get("id")]))
            $cartItems[$product->__get("id")] = ["last_updated" => new \DateTime(), "quantity" => $quantity];

        $_SESSION["cart"] = $cartItems;
    }

    /**
     * Get total of cart items by product price and quantity
     * @return float
     */
    public function getTotal(): float
    {
        $cart = $this->getCart();
        $total = 0.00;

        foreach ($cart as $item)
            $total += $item["total"];

        return $total;
    }
}
