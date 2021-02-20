<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\Order;
use App\FetzPetz\Model\Product;
use App\FetzPetz\Model\User;

class ShopController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/cart' => 'cart',
            '/cart/remove/{id}' => 'cartRemove',
            '/cart/quantity/{id}/{quantity}' => 'cartQuantity',
            '/wishlist' => 'wishlist',
            '/wishlist/remove/redirect/{id}' => 'wishlistRemoveRedirect',
            '/wishlist/remove/{id}' => 'wishlistRemove',
            '/wishlist/add/{id}' => 'wishlistAdd',
            '/profile' => 'profile',
            '/profile/orders' => 'profileOrders',
            '/profile/orders/{id}' => 'profileOrderView',
            '/profile/settings' => 'profileSettings'
        ];
    }

    public function cart()
    {
        $this->setParameter("title", "FetzPetz | Cart");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/profileSidebar.css"],
            ["type" => "stylesheet", "href" => "/assets/css/shop.css"]
        ]);

        $items = $this->kernel->getShopService()->getCart();
        $total = $this->kernel->getShopService()->getCartTotal();

        $this->setParameter("items", $items);
        $this->setParameter("total", $total);

        return $this->setView("shop/cart.php");
    }

    public function cartRemove($id)
    {
        $product = $this->kernel->getModelService()->findOneById(Product::class, $id);
        if ($product != null) {
            $this->kernel->getShopService()->removeFromCart($product);
            $this->kernel->getNotificationService()->pushNotification('Removed from cart', $product->name . ' has been removed from your cart.');
        }

        if ($_GET['source'] ?? '' === 'checkout')
            return $this->redirectTo('/checkout/summary');
        else
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
            return $this->redirectTo('/login?redirect_to=/wishlist');

        $this->setParameter("title", "FetzPetz | Wishlist");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/profileSidebar.css"],
            ["type" => "stylesheet", "href" => "/assets/css/shop.css"]
        ]);

        $items = $this->kernel->getShopService()->getWishlist($this->getUser());

        $this->setParameter("items", $items);

        return $this->setView("shop/wishlist.php");
    }

    public function wishlistRemoveRedirect($id)
    {
        $product = $this->kernel->getModelService()->findOneById(Product::class, $id);
        if ($product != null) {
            $this->kernel->getShopService()->removeFromWishlist($product, $this->getUser());
            $this->kernel->getNotificationService()->pushNotification('Removed from wishlist', $product->name . ' has been removed from your wishlist.');
        }
        return $this->redirectTo('/wishlist');
    }

    public function wishlistRemove($id)
    {
        $product = $this->kernel->getModelService()->findOneById(Product::class, $id);
        if ($product != null) $this->kernel->getShopService()->removeFromWishlist($product, $this->getUser());
        return $this->printJson([
            'result' => 'ok'
        ]);
    }

    public function wishlistAdd($id)
    {
        $product = $this->kernel->getModelService()->findOneById(Product::class, $id);
        if ($product != null) $this->kernel->getShopService()->addToWishlist($product, $this->getUser());
        return $this->printJson([
            'result' => 'ok'
        ]);
    }

    public function profile()
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectTo('/login?redirect_to=/profile');
        }

        return $this->redirectTo('/profile/orders');
    }

    public function profileOrders()
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectTo('/login?redirect_to=/profile');
        }

        $this->setParameter("title", "FetzPetz | My Orders");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/profileSidebar.css"],
            ["type" => "stylesheet", "href" => "/assets/css/shop.css"]
        ]);

        $orders = $this->kernel->getModelService()->find(Order::class, ['user_id' => $this->getUser()->id]);

        $this->setParameter('orders', $orders);

        return $this->setView("shop/orders.php");
    }

    public function profileOrderView($id)
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectTo('/login?redirect_to=/profile');
        }

        $order = $this->kernel->getModelService()->findOne(Order::class, ['user_id' => $this->getUser()->id, 'id' => $id]);
        if ($order == null) {
            $this->setParameter("title", "FetzPetz | 404 - Order not found");
            $this->setParameter('message', 'Order not found');
            return $this->setView("fallback/404.php");
        }

        $this->setParameter("title", "FetzPetz | Order #" . sprintf('%04d', $order->id));

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/profileSidebar.css"],
            ["type" => "stylesheet", "href" => "/assets/css/shop.css"]
        ]);

        $this->setParameter("order", $order);

        return $this->setView("shop/orderView.php");
    }

    public function profileSettings()
    {
        if (!$this->isAuthenticated()) {
            return $this->redirectTo('/login?redirect_to=/profile/settings');
        }

        $notificationService = $this->kernel->getNotificationService();
        $modelService = $this->kernel->getModelService();

        if (isset($_POST['updatePersonal'])) {
            $firstName = $_POST['firstName'] ?? '';
            $lastName = $_POST['lastName'] ?? '';

            if (!$this->isStringValid($firstName, 2, 100))
                $notificationService->pushNotification('Invalid Firstname', 'Your Firstname should be at least 2 characters long up to 100 characters', 'error');
            else if (!$this->isStringValid($lastName, 2, 50))
                $notificationService->pushNotification('Invalid Lastname', 'Your Lastname should be at least 2 characters long up to 50 characters', 'error');
            else {
                $user = $this->getUser();

                $user->firstname = $firstName;
                $user->lastname = $lastName;

                $modelService->update($user);
                $notificationService->pushNotification('Personal Data updated', 'Your Personal Data has been updated', 'success');
            }
        }

        if (isset($_POST['updateEmail'])) {
            $email = strtolower($_POST['email'] ?? '');

            $existingUser = $this->kernel->getModelService()->findOne(User::class, ['email' => $email]);

            if ($existingUser != null)
                $notificationService->pushNotification('Invalid E-Mail', 'The E-Mail is already in Use', 'error');
            else {
                $user = $this->getUser();

                $user->email = $email;
                $user->email_verified = 0;

                $modelService->update($user);
                $notificationService->pushNotification('E-Mail Address updated', 'Your E-Mail Address has been updated', 'success');
            }
        }

        if (isset($_POST['updatePassword'])) {
            $oldPassword = $_POST['oldPassword'] ?? '';
            $newPassword = $_POST['newPassword'] ?? '';
            $newPassword2 = $_POST['newPassword2'] ?? '';

            $user = $this->getUser();

            if (!password_verify($oldPassword, $user->password_hash))
                $notificationService->pushNotification('Invalid Password', 'Your Password is invalid', 'error');
            else if (strlen($newPassword) < 8) {
                $notificationService->pushNotification('Invalid Password', 'Your new Password should have at least 8 characters', 'error');
            } else if ($newPassword !== $newPassword2) {
                $notificationService->pushNotification('Invalid Password', 'Your new Password should match', 'error');
            } else {
                $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);

                $user->password_hash = $passwordHash;

                $modelService->update($user);
                $notificationService->pushNotification('Password updated', 'Your Password has been updated', 'success');
            }
        }


        $this->setParameter("title", "FetzPetz | Settings");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/profileSidebar.css"],
            ["type" => "stylesheet", "href" => "/assets/css/shop.css"]
        ]);

        return $this->setView("shop/settings.php");
    }

    private function isStringValid(string $value, int $min, int $max): bool
    {
        $length = strlen($value);

        if ($length < $min) return false;
        if ($length > $max) return false;
        return true;
    }
}
