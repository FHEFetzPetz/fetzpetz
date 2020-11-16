<?php

class IndexController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/' => 'index'
        ];
    }

    public function index() {
        return $this->renderView("index.php");
    }
}