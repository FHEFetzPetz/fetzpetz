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
            return $this->redirectTo('/login?redirect_to=/checkout/address');
        }

        $items = $this->kernel->getShopService()->getCart();

        if (sizeof($items) <= 0) {
            return $this->redirectTo('/cart');
        }

        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';
        $street = $_POST['street'] ?? '';
        $zip = $_POST['zip'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $country = $_POST['country'] ?? '';
        $phoneNumber = $_POST['phoneNumber'] ?? '';

        $billingAddress = $_POST['billingAddress'] ?? false;

        $billingFirstName = $_POST['billingFirstName'] ?? '';
        $billingLastName = $_POST['billingLastName'] ?? '';
        $billingStreet = $_POST['billingStreet'] ?? '';
        $billingZip = $_POST['billingZip'] ?? '';
        $billingCity = $_POST['billingCity'] ?? '';
        $billingState = $_POST['billingState'] ?? '';
        $billingCountry = $_POST['billingCountry'] ?? '';
        $billingPhoneNumber = $_POST['billingPhoneNumber'] ?? '';

        if(isset($_SESSION['checkout_address'])) {
            $address = $_SESSION['checkout_address'];

            $firstName = $_POST['firstName'] ?? $address['firstName'];
            $lastName = $_POST['lastName'] ?? $address['lastName'];
            $street = $_POST['street'] ?? $address['street'];
            $zip = $_POST['zip'] ?? $address['zip'];
            $city = $_POST['city'] ?? $address['city'];
            $state = $_POST['state'] ?? $address['state'];
            $country = $_POST['country'] ?? $address['country'];
            $phoneNumber = $_POST['phoneNumber'] ?? $address['phoneNumber'];

            if($address['billingAddress'] != null) {
                $billingAddress = $address['billingAddress'];

                $billingFirstName = $_POST['billingFirstName'] ?? $billingAddress['firstName'];
                $billingLastName = $_POST['billingLastName'] ?? $billingAddress['lastName'];
                $billingStreet = $_POST['billingStreet'] ?? $billingAddress['street'];
                $billingZip = $_POST['billingZip'] ?? $billingAddress['zip'];
                $billingCity = $_POST['billingCity'] ?? $billingAddress['city'];
                $billingState = $_POST['billingState'] ?? $billingAddress['state'];
                $billingCountry = $_POST['billingCountry'] ?? $billingAddress['country'];
                $billingPhoneNumber = $_POST['billingPhoneNumber'] ?? $billingAddress['phoneNumber'];
            }
        }

        $errors = [];

        $this->setParameter("firstName", $firstName);
        $this->setParameter("lastName", $lastName);
        $this->setParameter("street", $street);
        $this->setParameter("zip", $zip);
        $this->setParameter("city", $city);
        $this->setParameter("state", $state);
        $this->setParameter("country", $country);
        $this->setParameter("phoneNumber", $phoneNumber);

        $this->setParameter("billingAddress", $billingAddress);

        $this->setParameter("billingFirstName", $billingFirstName);
        $this->setParameter("billingLastName", $billingLastName);
        $this->setParameter("billingStreet", $billingStreet);
        $this->setParameter("billingZip", $billingZip);
        $this->setParameter("billingCity", $billingCity);
        $this->setParameter("billingState", $billingState);
        $this->setParameter("billingCountry", $billingCountry);
        $this->setParameter("billingPhoneNumber", $billingPhoneNumber);

        if (isset($_POST['firstName'])) {
            if (!$this->isStringValid($firstName, 2, 100))
                $errors[] = 'First name is invalid! (Shipping Address)';

            if (!$this->isStringValid($lastName, 2, 100))
                $errors[] = 'Last name is invalid! (Shipping Address)';

            if (!$this->isStringValid($street, 2, 150))
                $errors[] = 'Street and Number are invalid! (Shipping Address)';

            if (!$this->isStringValid($zip, 2, 15))
                $errors[] = 'ZIP - Code is invalid! (Shipping Address)';

            if (!$this->isStringValid($city, 2, 100))
                $errors[] = 'City is invalid! (Shipping Address)';

            if (!$this->isStringValid($state, 0, 100))
                $errors[] = 'State is invalid! (Shipping Address)';

            if (!$this->isStringValid($country, 2, 2))
                $errors[] = 'Country is invalid! (Shipping Address)';

            if (!$this->isStringValid($phoneNumber, 2, 30))
                $errors[] = 'Phone Number is invalid! (Shipping Address)';

            if($billingAddress) {
                if (!$this->isStringValid($billingFirstName, 2, 100))
                    $errors[] = 'First name is invalid! (Billing Address)';

                if (!$this->isStringValid($billingLastName, 2, 100))
                    $errors[] = 'Last name is invalid! (Billing Address)';

                if (!$this->isStringValid($billingStreet, 2, 150))
                    $errors[] = 'Street and Number are invalid! (Billing Address)';

                if (!$this->isStringValid($billingZip, 2, 15))
                    $errors[] = 'ZIP - Code is invalid! (Billing Address)';

                if (!$this->isStringValid($billingCity, 2, 100))
                    $errors[] = 'City is invalid! (Billing Address)';

                if (!$this->isStringValid($billingState, 0, 100))
                    $errors[] = 'State is invalid! (Billing Address)';

                if (!$this->isStringValid($billingCountry, 2, 2))
                    $errors[] = 'Country is invalid! (Billing Address)';

                if (!$this->isStringValid($billingPhoneNumber, 2, 30))
                    $errors[] = 'Phone Number is invalid! (Billing Address)';
            }

            if (sizeof($errors) <= 0) {
                $_SESSION['checkout_address'] = [
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'street' => $street,
                    'zip' => $zip,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'phoneNumber' => $phoneNumber,
                    'billingAddress' => $billingAddress ? [
                        'firstName' => $billingFirstName,
                        'lastName' => $billingLastName,
                        'street' => $billingStreet,
                        'zip' => $billingZip,
                        'city' => $billingCity,
                        'state' => $billingState,
                        'country' => $billingCountry,
                        'phoneNumber' => $billingPhoneNumber
                    ] : null
                ];

                if($_GET['type'] ?? '' === 'update')
                    return $this->redirectTo('/checkout/summary');
                else
                    return $this->redirectTo('/checkout/payment-method');
            }
        }

        $this->setParameter("title", "FetzPetz | Checkout");

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/checkout.css"]
        ]);



        $this->setParameter("errors", $errors);
        return $this->setView("checkout/address.php");
    }

    public function checkoutPaymentMethod()
    {

        if (!$this->isAuthenticated()) {
            return $this->redirectTo('/login?redirect_to=/checkout/payment-method');
        }

        $items = $this->kernel->getShopService()->getCart();

        if (sizeof($items) <= 0) {
            return $this->redirectTo('/cart');
        }

        $errors = [];

        $paymentMethod = $_POST['paymentMethod'] ?? ($_SESSION['checkout_payment_method'] ?? '');

        if (isset($_POST['paymentMethod'])) {
            switch ($paymentMethod) {
                case 'paypal': case 'creditcard' : case 'sepa' : case 'sofort' : break;
                default: $errors[] = 'Invalid Payment Method';
            }

            $_SESSION['checkout_payment_method'] = $paymentMethod;

            return $this->redirectTo('/checkout/summary');
        }

        $this->setParameter("title", "FetzPetz | Checkout");
        $this->setParameter('paymentMethod', $paymentMethod);

        $this->addExtraHeaderFields([
            ["type" => "stylesheet", "href" => "/assets/css/checkout.css"]
        ]);

        $this->setParameter("errors", $errors);
        $this->setView("checkout/paymentMethod.php");
    }

    public function checkoutSummary()
    {

        if (!$this->isAuthenticated()) {
            return $this->redirectTo('/login?redirect_to=/checkout/summary');
        }

        $items = $this->kernel->getShopService()->getCart();
        $total = $this->kernel->getShopService()->getCartTotal();

        if (sizeof($items) <= 0) {
            return $this->redirectTo('/cart');
        }

         if (!isset($_SESSION['checkout_address'])) {
             return $this->redirectTo('/checkout/address');
         }

         if (!isset($_SESSION['checkout_payment_method'])) {
            return $this->redirectTo('/checkout/payment-method');
        }

        $this->setParameter("items", $items);
        $this->setParameter("total", $total);

        $this->setParameter("address", $_SESSION['checkout_address']);
        $this->setParameter("paymentMethod", $_SESSION['checkout_payment_method']);

        $errors = [];

        if(isset($_POST['submit'])) {
            $shopService = $this->kernel->getShopService();

            $order = $shopService->placeOrder();
            if($order == null)
                $errors[] = 'Order could not be placed';
            else
                return $this->redirectTo('/profile/orders/' . $order->id);
        }

        $this->setParameter("title", "FetzPetz | Checkout");

        $this->addExtraHeaderFields([
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
