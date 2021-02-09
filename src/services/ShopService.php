<?php

namespace App\FetzPetz\Services;

use App\FetzPetz\Core\Service;
use App\FetzPetz\Model\Product;
use App\FetzPetz\Model\User;
use App\FetzPetz\Model\WishlistItem;

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
            $cartItem = $cartItems[$product->id];
            $total = $product->cost_per_item * $cartItem["quantity"];

            $output[] = array_merge(["product" => $product, "total" => $total], $cartItem);
        }

        return $output;
    }

    public function getProductInCart(Product $product): ?array
    {
        $cartItems = $_SESSION["cart"] ?? [];
        $id = $product->id;

        if (isset($cartItems[$id])) {
            $cartItem = $cartItems[$id];
            $total = $product->cost_per_item * $cartItem["quantity"];

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

        if (isset($cartItems[$product->id]))
            $cartItems[$product->id] = ["last_updated" => new \DateTime(), "quantity" => $quantity + $cartItems[$product->id]['quantity']];
        else
            $cartItems[$product->id] = ["last_updated" => new \DateTime(), "quantity" => $quantity];

        $_SESSION["cart"] = $cartItems;
    }

    /**
     * Removes the product from the cart if existing
     * @param Product $product
     */
    public function removeFromCart(Product $product)
    {
        $cartItems = $_SESSION["cart"] ?? [];

        if (isset($cartItems[$product->id]))
            unset($cartItems[$product->id]);

        $_SESSION["cart"] = $cartItems;
    }

    /**
     * Changes the quantity of a product if existing
     * @param Product $product
     * @param int $quantity
     */
    public function changeCartItemQuantity(Product $product, int $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($product);
            return;
        }
        $cartItems = $_SESSION["cart"] ?? [];

        if (isset($cartItems[$product->id]))
            $cartItems[$product->id] = ["last_updated" => new \DateTime(), "quantity" => $quantity];

        $_SESSION["cart"] = $cartItems;
    }

    /**
     * Get total of cart items by product price and quantity
     * @return float
     */
    public function getCartTotal(): float
    {
        $cart = $this->getCart();
        $total = 0.00;

        foreach ($cart as $item)
            $total += $item["total"];

        return $total;
    }

    private function isUserPresent(User $user = null) : ?User {
        if($user == null) {
            $securityService = $this->kernel->getSecurityService();
            if (!$securityService->isAuthenticated()) return null;
            $user = $securityService->getUser();
        }

        return $user;
    }

    public function getWishlist(User $user = null): array {
        $user = $this->isUserPresent($user);
        if(!$user) return [];

        $modelService = $this->kernel->getModelService();

        $wishlistItems = $modelService->find(WishlistItem::class, ["user_id" => $user->id]);
        $productIds = [];

        foreach($wishlistItems as $item)
            $productIds[$item->product_id] = null;

        $productEntries = $modelService->find(Product::class, ["id" => array_keys($productIds)]);
        foreach($productEntries as $product)
            $productIds[$product->id] = $product;

        $output = [];

        foreach($wishlistItems as $item) {
            if(isset($productIds[$item->product_id])) {
                $item->product = $productIds[$item->product_id];
                $item->user = $user;

                $output[] = $item;
            }
        }

        return $output;
    }

    public function getRawWishlist(User $user = null){
        $user = $this->isUserPresent($user);

        if(!$user) return [];

        $modelService = $this->kernel->getModelService();

        $wishlistItems = $modelService->find(WishlistItem::class, ["user_id" => $user->id]);
        
        $output = [];

        foreach($wishlistItems as $item)
            $output[] = $item->product_id;

        return $output;
    }

    public function addToWishlist(Product $product, User $user = null): ?WishlistItem {
        $user = $this->isUserPresent($user);

        if(!$user) return null;

        $modelService = $this->kernel->getModelService();

        $existingEntry = $modelService->findOne(WishlistItem::class, ["user_id" => $user->id, "product_id" => $product->id]);
        if($existingEntry != null) return $existingEntry;

        $wishlistItem = new WishlistItem([
            "user_id" => $user->id,
            "product_id" => $product->id
        ]);

        $modelService->insert($wishlistItem);

        return $wishlistItem;
    }

    public function removeFromWishlist(Product $product, User $user = null) {
        $user = $this->isUserPresent($user);
        if(!$user) return null;

        $modelService = $this->kernel->getModelService();

        $existingEntry = $modelService->findOne(WishlistItem::class, ["user_id" => $user->id, "product_id" => $product->id]);
        if($existingEntry == null) return null;

        $modelService->destroy($existingEntry);
    }
}
