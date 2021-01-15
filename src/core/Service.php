<?php

namespace App\FetzPetz\Core;

use App\FetzPetz\Kernel;

/**
 * Base Class for Service which is access to the kernel
 * @package App\FetzPetz\Core
 */
class Service
{

    protected $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

}