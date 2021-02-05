<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\Category;
use App\FetzPetz\Model\Product;
use App\FetzPetz\Model\User;

class ShopController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/cart' => 'cart',
            '/cart-test' => 'cartTest',
            '/cart/remove/{id}' => 'cartRemove',
            '/cart/quantity/{id}/{quantity}' => 'cartQuantity',
            '/wishlist' => 'wishlist',
        ];
    }

    public function cart()
    {
        $this->setParameter("title", "FetzPetz | Cart");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/profile.css"],
            ["type" => "stylesheet", "href" => "/assets/css/cart.css"]
        ]);

        $items = $this->kernel->getShopService()->getCart();
        $total = $this->kernel->getShopService()->getCartTotal();

        $this->setParameter("items", $items);
        $this->setParameter("total", $total);

        $this->setView("shop/cart.php");
    }

    public function cartTest()
    {
        $product = $this->kernel->getModelService()->findOne(Product::class);
        $this->kernel->getShopService()->addToCart($product, 2);

        $this->redirectTo("/cart");
    }

    public function cartRemove($id)
    {
        $product = $this->kernel->getModelService()->findOneById(Product::class, $id);
        if ($product != null) $this->kernel->getShopService()->removeFromCart($product);
        return $this->redirectTo('/cart');
    }

    public function cartQuantity($id, $quantity)
    {
        $product = $this->kernel->getModelService()->findOneById(Product::class, $id);
        if ($product == null) return $this->redirectTo('/cart');

        $shopService = $this->kernel->getShopService();

        $shopService->changeCartItemQuantity($product, $quantity);

        $cartProduct = $shopService->getProductInCart($product);

        return $this->printJson([
            "changed_product" => $cartProduct,
            "total" => $shopService->getCartTotal(),
            "item_count" => sizeof($shopService->getCart())
        ]);
    }

    public function wishlist()
    {
        if (!$this->kernel->getSecurityService()->isAuthenticated())
            return $this->redirectTo('/login');

        $this->setParameter("title", "FetzPetz | Wishlist");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/profile.css"],
            ["type" => "stylesheet", "href" => "/assets/css/wishlist.css"]
        ]);

        $items = [];

        $this->setParameter("items", $items);

        $this->setView("shop/wishlist.php");
    }
}
