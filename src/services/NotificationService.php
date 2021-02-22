<?php

namespace App\FetzPetz\Services;

use App\FetzPetz\Core\Service;

class NotificationService extends Service
{

    const NOTIFICATION_IDENTIFIER = 'notifications';

    /**
     * returns the notifications stored in the session
     * and deletes them if declared
     *
     * @param boolean $clearAfterRetrieval
     * @return void
     */
    public function getNotifications($clearAfterRetrieval = true) {
        $output = $_SESSION[self::NOTIFICATION_IDENTIFIER] ?? [];
        if($clearAfterRetrieval) $this->clearNotifications();
        return $output;
    }

    /**
     * adds a notification to the session
     *
     * @param string $title
     * @param string $message
     * @param string $type
     * @return void
     */
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
