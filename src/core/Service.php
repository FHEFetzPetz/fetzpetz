<?php

namespace App\FetzPetz\Core;

use App\FetzPetz\Kernel;

class Service
{

    protected $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

}