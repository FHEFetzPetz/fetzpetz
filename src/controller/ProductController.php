<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\Product;
use App\FetzPetz\Model\User;

class ProductController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/product' => 'product',
        ];
    }

    public function product() {
        $this->setParameter("title", "FetzPetz | Product Page");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/product.css"]
        ]);

        $product = $this->kernel->getModelService()->findOne(Product::class);

        $products = $this->kernel->getModelService()->find(Product::class);
        
        $this->setParameter("products", $products);

        $this->setParameter("shownProduct", $product);

        $this->setView("product.php");
    }
}