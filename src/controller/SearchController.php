<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\Product;

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

        $queryString = 'SELECT * FROM ' . Product::TABLENAME . ' WHERE active = 1 AND (
            name LIKE \'%' . $query . '%\' OR
            description LIKE \'%' . $query . '%\')';

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