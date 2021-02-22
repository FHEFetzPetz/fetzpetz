<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\Category;
use App\FetzPetz\Model\Product;
use App\FetzPetz\Model\ProductCategory;

class SearchController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/search' => 'search'
        ];
    }

    public function search() {
        if(!isset($_GET['query'])) return $this->redirectTo('/');

        $query = $_GET['query'];

        $database = $this->kernel->getDatabase();

        $queryString = 'SELECT *, p.name as name, p.description as description, p.id as id FROM ' . Product::TABLENAME . ' as p 
            LEFT JOIN ' . ProductCategory::TABLENAME . ' as pc ON pc.product_id = p.id 
            LEFT JOIN ' . Category::TABLENAME . ' as c ON c.id = pc.category_id WHERE p.active = 1 AND (
            p.name LIKE \'%' . $query . '%\' OR
            p.description LIKE \'%' . $query . '%\' OR
            c.name LIKE \'%' . $query . '%\' OR
            c.description LIKE \'%' . $query . '%\')';

        $result = [];
        $products = [];

        try {
            $result = $database->query($queryString)->fetchAll();
        } catch(\PDOException $exception) {
            die('Select statement failed: ' . $exception->getMessage());
        }

        foreach($result as $item) {
            $products[] = new Product($item,true);
        }

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/mainpage.css"]
        ]);

        $this->setParameter("title", "FetzPetz | Suche - $query");

        $wishlist = $this->kernel->getShopService()->getRawWishlist($this->getUser());

        $this->setParameter("query", $query);

        $this->setParameter("products", $products);
        $this->setParameter("wishlist", $wishlist);
        $this->setParameter("slim", true);

        return $this->setView("index.php");
    }

}