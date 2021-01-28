<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;

class FallbackController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '404' => 'page404'
        ];
    }

    public function page404() {
        $this->setParameter("title", "FetzPetz | 404 - Page not found");
        $this->setParameter('message','Page not found');
        return $this->setView("fallback/404.php");
    }

}