<?php

class IndexController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/' => 'index',
            '/einkaufswagen' => 'shoppingCart'
        ];
    }

    public function index() {
        return $this->renderView("index.php");
    }

    public function shoppingCart() {
        $this->kernel->getPaymentHandler()->performPayment(10,32);
        return $this->renderView("shoppingCart.php");
    }
}