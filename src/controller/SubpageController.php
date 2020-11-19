<?php

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