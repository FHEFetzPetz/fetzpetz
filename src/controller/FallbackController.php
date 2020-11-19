<?php


class FallbackController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '404' => 'page404'
        ];
    }

    public function page404() {
        return $this->renderView("fallback/404.php");
    }

}