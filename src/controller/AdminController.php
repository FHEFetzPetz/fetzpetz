<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\Category;
use App\FetzPetz\Model\Product;
use App\FetzPetz\Model\User;

class AdminController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/admin' => 'admin'
        ];
    }

    public function admin() {
        if(!$this->isAdministrator()) return $this->redirectTo('/login');

        $this->setParameter("title", "FetzPetz | Administration");

        $this->setTemplate('administration');
        $this->setView("admin/index.php");
    }
}