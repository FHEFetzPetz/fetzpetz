<?php


namespace App\FetzPetz\Services;

use App\FetzPetz\Components\Model;
use App\FetzPetz\Core\Service;
use App\FetzPetz\Model\User;


class SecurityService extends Service
{
    private $currentUser = null;

    /**
     * Prepares the session by checking if the user
     * has any session parameters and if the given
     * values are still valid, otherwise it will be
     * destroyed
     */
    public function prepareService() {
        if(isset($_SESSION["user_id"])) {
            $id = intval($_SESSION["user_id"]);

            $user = $this->checkUserExistence($id);
            if($user) $this->currentUser = $user;
            else $this->destroySession(true);
        }
    }

    /**
     * Updating the session and adding the current
     * user to the service
     *
     * @param User $user
     */
    public function authenticateWithUser(User $user) {
        $_SESSION["user_id"] = $user->__get("id");
        $this->currentUser = $user;
    }

    /**
     * Destroys the session and removing the
     * current user from the service
     *
     * @param bool $recreate
     */
    public function destroySession($recreate = true) {
        session_destroy();
        $this->currentUser = null;

        if($recreate) session_start();
    }

    public function checkUserExistence(int $id): ?User {
        return $this->kernel->getModelService()->findOneById(User::class, $id);
    }

    public function getUser(): ?User {
        return $this->currentUser;
    }

    public function isAuthenticated(): bool {
       return $this->currentUser != null;
    }

}