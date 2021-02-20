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
            '/signup' => 'signup',
            '/logout' => 'logout',
        ];
    }

    public function login()
    {
        $email = strtolower($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];

        $this->setParameter("title", "FetzPetz | Login");

        if (isset($_POST['email'])) {

            $password = $_POST['password'];

            $existingUser = $this->kernel->getModelService()->findOne(User::class, ['email' => $email]);

            if (!is_null($existingUser) && password_verify($password, $existingUser->password_hash)) {
                if(!$existingUser->active) {
                    $this->kernel->getNotificationService()->pushNotification('Account suspended', 'The account has been suspended.', 'warning');
                    return $this->redirectTo('/login');
                }

                $this->kernel->getSecurityService()->authenticateWithUser($existingUser);

                if(strlen($_GET['redirect_to'] ?? '') > 0)
                    $this->redirectTo($_GET['redirect_to']);
                else {
                    $this->kernel->getNotificationService()->pushNotification('Login successful', 'You are now logged in as ' . $existingUser->firstname . ' ' . $existingUser->lastname, 'success');
                    $this->redirectTo('/');
                }
            } else $errors[] = 'invalid credentials';
        }

        $this->setParameter('errors', $errors);

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/authentication.css"]
        ]);

        return $this->setView("authentication/login.php");
    }

    public function signup()
    {
        $firstName = $_POST['firstname'] ?? '';
        $lastName = $_POST['lastname'] ?? '';
        $email = strtolower($_POST['email'] ?? '');

        $errors = [];

        $this->setParameter("firstName", $firstName);
        $this->setParameter("lastName", $lastName);
        $this->setParameter("email", $email);

        $this->setParameter("title", "FetzPetz | Sign Up");

        if (isset($_POST['email'])) {
            $password = $_POST['password'];
            $repeatPassword = $_POST['repeat-password'];

            $existingUser = $this->kernel->getModelService()->findOne(User::class, ['email' => $email]);

            if (!is_null($existingUser))
                $errors[] = 'Email-Address is already in use!';

            if (!$this->isStringValid($firstName, 2, 100))
                $errors[] = 'First name is invalid!';

            if (!$this->isStringValid($lastName, 2, 50))
                $errors[] = 'Last name is invalid!';

            if (strlen($password) < 8)
                $errors[] = 'Password should be at least 8 characters long!';

            if ($password != $repeatPassword)
                $errors[] = 'Passwords should match!';

            if (sizeof($errors) == 0) {
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                $user = new User([
                    'firstname' => $firstName,
                    'lastname' => $lastName,
                    'email' => $email,
                    'password_hash' => $passwordHash,
                    'created_at' => new \DateTime()
                ]);

                $this->kernel->getModelService()->insert($user);
                $this->kernel->getSecurityService()->authenticateWithUser($user);

                if(strlen($_GET['redirect_to'] ?? '') > 0)
                    $this->redirectTo($_GET['redirect_to']);
                else {
                    $this->kernel->getNotificationService()->pushNotification('Registration successful', 'You are now logged in as ' . $user->firstname . ' ' . $user->lastname, 'success');
                    $this->redirectTo('/');
                }
            }
        }

        $this->setParameter('errors', $errors);

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/authentication.css"]
        ]);

        return $this->setView("authentication/signup.php");
    }

    private function isStringValid(string $value, int $min, int $max): bool
    {
        $length = strlen($value);

        if ($length < $min) return false;
        if ($length > $max) return false;
        return true;
    }

    public function logout() {
        $this->kernel->getSecurityService()->removeAuthentication();
        $this->kernel->getNotificationService()->pushNotification('Logout successful', 'You have been logged out', 'success');
        return $this->redirectTo('/');
    }
}
