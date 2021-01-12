<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;
use App\FetzPetz\Model\User;

class AuthenticationController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/login' => 'login',
            '/logout' => 'logout',
            '/security' => 'security'
        ];
    }

    public function login() {
        $this->setParameter("title", "FetzPetz | Login");

        $user = $this->kernel->getModelService()->findOne(User::class);

        if(isset($_POST["submit"])) {
            if($user) {
                $this->kernel->getSecurityService()->authenticateWithUser($user);
                $this->redirectTo("/security");
            }
        }

        $this->setParameter("user", $user);
        $this->setParameter("authenticatedUser", $this->getUser());

        $this->setView("authentication/login.php");
    }

    public function logout() {
        $this->kernel->getSecurityService()->destroySession(true);
        $this->redirectTo("/login");
    }

    public function security() {
        if(!$this->isAuthenticated()) die("Not authenticated");

        $this->setParameter("title", "Es ist Mittwoch (meine Freunde)");

        $this->setView("test.php");
    }
}