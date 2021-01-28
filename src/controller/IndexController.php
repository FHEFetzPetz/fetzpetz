<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\Product;
use App\FetzPetz\Model\User;

class IndexController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/' => 'index',
            '/test' => 'test'
        ];
    }

    public function index() {
        $this->setParameter("title", "FetzPetz | Main Page");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/mainpage.css"]
        ]);

        $products = $this->kernel->getModelService()->find(Product::class);

        $this->setParameter("products", $products);
        $this->setParameter("slim", true);

        $this->setView("index.php");
    }

    public function test() {
        $this->setParameter("title", "Es ist Mittwoch (meine Freunde)");
        $this->setParameter("navigation", false);

        $this->setView("test.php");
    }
}