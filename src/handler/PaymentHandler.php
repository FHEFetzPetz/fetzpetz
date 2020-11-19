<?php


class PaymentHandler extends Handler
{

    public function performPayment(int $a, int $b) {
        $c = $a + $b;
        echo "Das Ergebnis ist $c";
    }

}