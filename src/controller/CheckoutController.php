<?php

namespace App\FetzPetz\Controller;

use App\FetzPetz\Components\Controller;

class CheckoutController extends Controller
{

    public function shareRoutes(): array
    {
        return [
            '/checkout/address' => 'checkoutAddress',
            '/checkout/payment-method' => 'checkoutPaymentMethod',
            '/checkout/summary' => 'checkoutSummary'
        ];
    }


    public function checkoutAddress()
    {

        if (!$this->isAuthenticated()) {
            return $this->redirectTo('/login');
        }

        $items = $this->kernel->getShopService()->getCart();

        if (sizeof($items) <= 0) {
            return $this->redirectTo('/cart');
        }

        $firstName = $_POST['firstname'] ?? '';
        $lastName = $_POST['lastname'] ?? '';
        $street = $_POST['street'] ?? '';
        $zip = $_POST['zip'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $country = $_POST['country'] ?? '';
        $phoneNumber = $_POST['phoneNumber'] ?? '';

        $errors = [];

        $this->setParameter("firstName", $firstName);
        $this->setParameter("lastName", $lastName);
        $this->setParameter("street", $street);
        $this->setParameter("zip", $zip);
        $this->setParameter("city", $city);
        $this->setParameter("state", $state);
        $this->setParameter("country", $country);
        $this->setParameter("phoneNumber", $phoneNumber);

        if (isset($_POST['firstname'])) {
            if (!$this->isStringValid($firstName, 2, 100))
                $errors[] = 'First name is invalid!';

            if (!$this->isStringValid($lastName, 2, 100))
                $errors[] = 'Last name is invalid!';

            if (!$this->isStringValid($street, 2, 150))
                $errors[] = 'Street and Number are invalid!';

            if (!$this->isStringValid($zip, 2, 15))
                $errors[] = 'ZIP - Code is invalid!';

            if (!$this->isStringValid($city, 2, 100))
                $errors[] = 'City is invalid!';

            if (!$this->isStringValid($state, 0, 100))
                $errors[] = 'State is invalid!';

            if (!$this->isStringValid($country, 2, 2))
                $errors[] = 'Country is invalid!';

            if (!$this->isStringValid($phoneNumber, 2, 30))
                $errors[] = 'Phone Number is invalid!';

            if (sizeof($errors) <= 0) {
                $_SESSION['checkout_address'] = [
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'street' => $street,
                    'zip' => $zip,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'phoneNumber' => $phoneNumber
                ];

                return $this->redirectTo('/checkout/payment-method');
            }
        }

        $this->setParameter("title", "FetzPetz | Checkout");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/profile.css"],
            ["type" => "stylesheet", "href" => "/assets/css/checkout.css"]
        ]);



        $this->setParameter("errors", $errors);
        $this->setView("checkout/address.php");
    }

    public function checkoutPaymentMethod()
    {

        if (!$this->isAuthenticated()) {
            return $this->redirectTo('/login');
        }

        $items = $this->kernel->getShopService()->getCart();

        if (sizeof($items) <= 0) {
            return $this->redirectTo('/cart');
        }

        $errors = [];

        if (isset($_POST['paymentMethod'])) {
            $paymentMethod = $_POST['paymentMethod'];

            switch ($paymentMethod) {
                case 'paypal': case 'creditcard' : case 'sepa' : case 'sofort' : break;
                default: $errors[] = 'Invalid Payment Method';
            }

            $_SESSION['checkout_payment_method'] = $paymentMethod;
            return $this->redirectTo('/checkout/summary');
        }

        $this->setParameter("title", "FetzPetz | Checkout");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/profile.css"],
            ["type" => "stylesheet", "href" => "/assets/css/checkout.css"]
        ]);

        $this->setParameter("errors", $errors);
        $this->setView("checkout/paymentMethod.php");
    }

    public function checkoutSummary()
    {

        if (!$this->isAuthenticated()) {
            return $this->redirectTo('/login');
        }

        $items = $this->kernel->getShopService()->getCart();

        if (sizeof($items) <= 0) {
            return $this->redirectTo('/cart');
        }

         if (!isset($_SESSION['checkout_address'])) {
             return $this->redirectTo('/checkout/address');
         }

         if (!isset($_SESSION['checkout_payment_method'])) {
            return $this->redirectTo('/checkout/payment-method');
        }

        $errors = [];

        $this->setParameter("title", "FetzPetz | Checkout");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/profile.css"],
            ["type" => "stylesheet", "href" => "/assets/css/checkout.css"]
        ]);

        $this->setParameter("errors", $errors);
        $this->setView("checkout/summary.php");
    }

    private function isStringValid(string $value, int $min, int $max): bool
    {
        $length = strlen($value);

        if ($length < $min) return false;
        if ($length > $max) return false;
        return true;
    }
}
