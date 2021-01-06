<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\User;

class IndexController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/' => 'index'
        ];
    }

    public function index() {
        $this->setParameter("title", "FetzPetz | Main Page");
        $this->setView("index.php");
    }
}