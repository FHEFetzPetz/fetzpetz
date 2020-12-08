<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;

class SubpageController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/subpage' => 'subpage'
        ];
    }

    public function subpage() {
        return $this->renderView("subpage.php");
    }
}