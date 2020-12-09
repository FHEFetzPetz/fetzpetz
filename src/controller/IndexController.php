<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\User;

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

      //  $user = $this->kernel->getModelService()->findOne(User::class);
      //  $user->__set('firstname','lili');

     //   $this->kernel->getModelService()->update($user);

      //  print_r($user);

      //  $this->kernel->getModelService()->destroy($user);

        return $this->renderView("index.php");
    }

    public function shoppingCart() {
        $this->kernel->getPaymentService()->performPayment(10,32);
        return $this->renderView("shoppingCart.php");
    }
}