<div class="container">
    <section id="checkout">
        <div class="box payment-method">
            <h1>Checkout</h1>
            <h2>Payment Method</h2>
            <form method="post" class="form">
                <?php if (sizeof($errors) > 0) : ?>
                    <ul class="errors">
                        <?php foreach ($errors as $error) : ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <ul class="payment-method">
                    <li>
                        <input type="radio" name="paymentMethod" id="paypal" value="paypal"<?= $paymentMethod == 'paypal' ? ' checked': '' ?>>
                        <label for="paypal">PayPal</label>
                    </li>
                    <li>
                        <input type="radio" name="paymentMethod" id="creditcard" value="creditcard"<?= $paymentMethod == 'creditcard' ? ' checked': '' ?>>
                        <label for="creditcard">Credit Card (Mastercard, VISA)</label>
                    </li>
                    <li>
                        <input type="radio" name="paymentMethod" id="sepa" value="sepa"<?= $paymentMethod == 'sepa' ? ' checked': '' ?>>
                        <label for="sepa">SEPA direct debit</label>
                    </li>
                    <li>
                        <input type="radio" name="paymentMethod" id="sofort" value="sofort"<?= $paymentMethod == 'sofort' ? ' checked': '' ?>>
                        <label for="sofort">SOFORT</label>
                    </li>
                </ul>
                <div class="submit-item">
                    <button>Continue ♥</button>
                </div>
            </form>
        </div>
    </section>
</div>