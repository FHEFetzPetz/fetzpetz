<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;

class CustomerController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/customer' => 'customer'
        ];
    }

    public function customer() {
        return $this->renderView("customer.php");
    }
}