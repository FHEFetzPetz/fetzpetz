<?php

class TestController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/test' => 'test'
        ];
    }

    public function test() {
        return $this->renderView("test.php");
    }
}