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
        ];
    }

    public function login()
    {
        $this->setParameter("title", "FetzPetz | Login");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/authentication.css"]
        ]);

        $this->setView("authentication/login.php");
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
            $reapeatPassword = $_POST['repeat-password'];

            $existingUser = $this->kernel->getModelService()->findOne(User::class, ['email' => $email]);

            if (!is_null($existingUser))
                $errors[] = 'Email-Address is already in use!';

            if (!$this->isStringValid($firstName, 2, 100))
                $errors[] = 'First name is invalid!';

            if (!$this->isStringValid($lastName, 2, 50))
                $errors[] = 'Last name is invalid!';

            if (strlen($password) < 8)
                $errors[] = 'Password should be at least 8 characters long!';

            if ($password != $reapeatPassword)
                $errors[] = 'Passwords should match!';

            if (sizeof($errors) == 0) {
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                $user = new User([
                    'firstname' => $firstName,
                    'lastname' => $lastName,
                    'email' => $email,
                    'password_hash' => $passwordHash,
                    'birthday' => new \DateTime(),
                    'created_at' => new \DateTime()
                ]);

                $this->kernel->getModelService()->insert($user);
                $this->kernel->getSecurityService()->authenticateWithUser($user);

                //TODO ZIELSEITE 
                $this->redirectTo('/');
            }
        }

        $this->setParameter('errors', $errors);

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/authentication.css"]
        ]);

        $this->setView("authentication/signup.php");
    }

    private function isStringValid(string $value, int $min, int $max): bool
    {
        $length = strlen($value);

        if ($length < $min) return false;
        if ($length > $max) return false;
        return true;
    }
}
