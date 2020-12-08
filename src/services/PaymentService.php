<?php

namespace App\FetzPetz\Services;

use App\FetzPetz\Core\Service;

class PaymentService extends Service
{

    public function performPayment(int $a, int $b) {
        $c = $a + $b;
        echo "Das Ergebnis ist $c";
    }

}