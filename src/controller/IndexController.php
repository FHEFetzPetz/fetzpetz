<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\Category;
use App\FetzPetz\Model\Product;

class IndexController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/' => 'index',
            '/category/{id}' => 'category',
            '/imprint' => 'imprint',
            '/privacy-policy' => 'privacyPolicy',
            '/terms-of-service' => 'termsOfService',
            '/faq' => 'faq',
            '/documentation' => 'documentation'
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

        return $this->setView("index.php");
    }

    public function category($id) {

        $category = $this->kernel->getModelService()->findOneById(Category::class,$id);

        if ($category == null || !$category->active)
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

        return $this->setView("index.php");
    }

    public function imprint() {
        $this->setParameter("title", "FetzPetz | Imprint");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/standalone.css"]
        ]);


        return $this->setView("standalone/imprint.php");
    }

    public function privacyPolicy() {
        $this->setParameter("title", "FetzPetz | Privacy Policy");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/standalone.css"]
        ]);

        return $this->setView("standalone/privacyPolicy.php");
    }

    public function termsOfService() {
        $this->setParameter("title", "FetzPetz | Terms of Service");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/standalone.css"]
        ]);

        return $this->setView("standalone/termsOfService.php");
    }

    public function faq() {
        $this->setParameter("title", "FetzPetz | FAQ");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/standalone.css"]
        ]);

        return $this->setView("standalone/faq.php");
    }

    public function documentation() {
        $this->setParameter("title", "FetzPetz | Documentation");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/standalone.css"]
        ]);

        return $this->setView("standalone/documentation.php");
    }
}