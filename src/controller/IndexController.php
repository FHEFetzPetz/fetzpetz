<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
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

        $this->setView("index.php");
    }

    public function test() {
        $this->setParameter("title", "Es ist Mittwoch (meine Freunde)");
        $this->setParameter("navigation", false);

        $this->setView("test.php");
    }
}