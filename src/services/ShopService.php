<?php

namespace App\FetzPetz\Services;

use App\FetzPetz\Core\Service;
use App\FetzPetz\Model\Address;
use App\FetzPetz\Model\Order;
use App\FetzPetz\Model\OrderItem;
use App\FetzPetz\Model\PaymentReference;
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

    /**
     * returns the product if it is stored in the cart
     *
     * @param Product $product
     * @return array|null
     */
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

    /**
     * returns the current user if the provided user is null
     *
     * @param User $user
     * @return User|null
     */
    private function isUserPresent(User $user = null) : ?User {
        if($user == null) {
            $securityService = $this->kernel->getSecurityService();
            if (!$securityService->isAuthenticated()) return null;
            $user = $securityService->getUser();
        }

        return $user;
    }

    /**
     * returns the wihslist of the given user
     *
     * @param User $user
     * @return array
     */
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

    /**
     * returns the wishlist without the real products
     *
     * @param User $user
     * @return array
     */
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

    /**
     * adds a product to the wishlist, if it is not already stored
     *
     * @param Product $product
     * @param User $user
     * @return WishlistItem|null
     */
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

    /**
     * removes product from the wishlist, if it is stored
     *
     * @param Product $product
     * @param User $user
     * @return void
     */
    public function removeFromWishlist(Product $product, User $user = null) {
        $user = $this->isUserPresent($user);
        if(!$user) return null;

        $modelService = $this->kernel->getModelService();

        $existingEntry = $modelService->findOne(WishlistItem::class, ["user_id" => $user->id, "product_id" => $product->id]);
        if($existingEntry == null) return null;

        $modelService->destroy($existingEntry);
    }

    /**
     * places a order based on the session variables
     *
     * @return Order|null
     */
    public function placeOrder(): ?Order {
        $cart = $this->getCart();
        $securityService = $this->kernel->getSecurityService();

        if(
            sizeof($cart) < 0 ||
            !$securityService->isAuthenticated() ||
            !isset($_SESSION['checkout_address']) ||
            !isset($_SESSION['checkout_payment_method'])) return null;

        $modelService = $this->kernel->getModelService();
        $user = $this->kernel->getSecurityService()->getUser();

        $address = $_SESSION['checkout_address'];

        $paymentReference = new PaymentReference([
            'payment_method' => $_SESSION['checkout_payment_method'],
            'payment_data' => null,
            'created_at' => new \DateTime()
        ]);

        $shippingAddress = new Address([
            'firstname' => $address['firstName'],
            'lastname' => $address['lastName'],
            'street' => $address['street'],
            'zip' => $address['zip'],
            'city' => $address['city'],
            'state' => $address['state'],
            'country' => $address['country'],
            'phone_number' => $address['phoneNumber']
        ]);

        $billingAddress = null;

        if($address['billingAddress'] != null) {
            $billingAddressData = $address['billingAddress'];

            $billingAddress = new Address([
                'firstname' => $billingAddressData['firstName'],
                'lastname' => $billingAddressData['lastName'],
                'street' => $billingAddressData['street'],
                'zip' => $billingAddressData['zip'],
                'city' => $billingAddressData['city'],
                'state' => $billingAddressData['state'],
                'country' => $billingAddressData['country'],
                'phone_number' => $billingAddressData['phoneNumber']
            ]);
        }

        $modelService->insert($paymentReference);
        $modelService->insert($shippingAddress);
        if($billingAddress) $modelService->insert($billingAddress);

        $order = new Order([
            'user_id' => $user->id,
            'payment_reference_id' => $paymentReference->id,
            'shipping_address_id' => $shippingAddress->id,
            'billing_address_id' => $billingAddress ? $billingAddress->id : null,
            'order_status' => 'pending',
            'shipment_data' => null,
            'created_at' => new \DateTime()
        ]);

        $modelService->insert($order);

        foreach($cart as $item) {
            $product = $item["product"];
            $quantity = $item['quantity'];

            $orderItem = new OrderItem([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'amount' => $quantity,
                'cost_per_item' => $product->cost_per_item,
                'item_data' => null
            ]);

            $modelService->insert($orderItem);
        }

        unset($_SESSION["cart"]);
        unset($_SESSION["checkout_address"]);
        unset($_SESSION["checkout_payment_method"]);

        $this->kernel->getNotificationService()->pushNotification('Order placed', 'Your order has been placed successfully and will be shipped shortly!', 'success');

        return $order;
    }
}
