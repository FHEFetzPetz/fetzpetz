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
            '/product/{id}' => 'product',
        ];
    }

    public function product($id) {
        $this->setParameter("title", "FetzPetz | Product Page");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/product.css"]
        ]);

        $product = $this->kernel->getModelService()->findOneById(Product::class, $id);
        if ($product == null) return $this->redirectTo('/');

        $products = $this->kernel->getModelService()->find(Product::class);
        
        $this->setParameter("products", $products);

        $this->setParameter("shownProduct", $product);

        $this->setView("product.php");
    }
}