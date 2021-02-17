<?php

namespace App\FetzPetz\Services;

use App\FetzPetz\Core\Service;
use App\FetzPetz\Model\Address;
use App\FetzPetz\Model\Order;
use App\FetzPetz\Model\OrderItem;
use App\FetzPetz\Model\PaymentReference;
use App\FetzPetz\Model\Product;
use App\FetzPetz\Model\User;
use App\FetzPetz\Model\WishlistItem;

class NotificationService extends Service
{

    const NOTIFICATION_IDENTIFIER = 'notifications';

    public function getNotifications($clearAfterRetrieval = true) {
        $output = $_SESSION[self::NOTIFICATION_IDENTIFIER] ?? [];
        if($clearAfterRetrieval) $this->clearNotifications();
        return $output;
    }

    public function pushNotification($title, $message, $type = 'info') {
        if(!isset($_SESSION[self::NOTIFICATION_IDENTIFIER]))
            $_SESSION[self::NOTIFICATION_IDENTIFIER] = [];
        $_SESSION[self::NOTIFICATION_IDENTIFIER][] = [
            'title' => $title,
            'message' => $message,
            'type' => $type
        ];
    }

    public function clearNotifications() {
        unset($_SESSION[self::NOTIFICATION_IDENTIFIER]);
    }

}
