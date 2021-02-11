<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\Category;
use App\FetzPetz\Model\Product;
use App\FetzPetz\Model\User;

class IndexController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/' => 'index',
            '/category/{id}' => 'category', 
        ];
    }

    public function index() {
        $this->setParameter("title", "FetzPetz | Main Page");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/mainpage.css"]
        ]);

        $products = $this->kernel->getModelService()->find(Product::class);
        $wishlist = $this->kernel->getShopService()->getRawWishlist($this->getUser());

        $this->setParameter("products", $products);
        $this->setParameter("wishlist", $wishlist);
        $this->setParameter("slim", true);

        $this->setView("index.php");
    }

    public function category($id) {

        $category = $this->kernel->getModelService()->findOneById(Category::class,$id);

        if ($category==null)
            return $this->redirectTo('/');

        $this->setParameter("title", "FetzPetz | Main Page");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/mainpage.css"]
        ]);

        $products = $category->getProducts($this->kernel->getModelService());
        $wishlist = $this->kernel->getShopService()->getRawWishlist($this->getUser());

        $this->setParameter("selectedCategory", $category);
        $this->setParameter("products", $products);
        $this->setParameter("wishlist", $wishlist);
        $this->setParameter("slim", true);

        $this->setView("index.php");
    }


}