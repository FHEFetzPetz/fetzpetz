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
            '/product/{id}/cart' => 'productCart',
        ];
    }

    public function product($id) {
        

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/product.css"]
        ]);

        $product = $this->kernel->getModelService()->findOneById(Product::class, $id);
        if ($product == null) {
            $this->setParameter("title", "FetzPetz | 404 - Product not found");
            $this->setParameter('message','Product not found');
            return $this->setView("fallback/404.php");
        }

        $this->setParameter("title", "FetzPetz | " . $product->__get('name'));

        $products = $this->kernel->getModelService()->find(Product::class);
        
        $this->setParameter("products", $products);

        $this->setParameter("shownProduct", $product);

        $this->setView("product.php");
    }

    public function productCart($id) {
        $product = $this->kernel->getModelService()->findOneById(Product::class, $id);
        if ($product == null) {
            $this->setParameter("title", "FetzPetz | 404 - Product not found");
            $this->setParameter('message','Product not found');
            return $this->setView("fallback/404.php");
        }

        $this->kernel->getShopService()->addToCart($product, 1);

        return $this->redirectTo('/cart');
    }
}