<?php

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