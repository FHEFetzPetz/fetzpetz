<div class="container">
    <section id="checkout">
        <div class="box address">
            <h1>Checkout</h1>
            <h2>Shipping Address</h2>
            <form method="post" class="form">
                <?php if (sizeof($errors) > 0) : ?>
                    <ul class="errors">
                        <?php foreach ($errors as $error) : ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="input-item">
                    <input id="firstName" name="firstName" type="text" placeholder="FirstName" maxlenght="100" value="<?= $firstName; ?>" required>
                    <input id="lastName" name="lastName" type="text" placeholder="LastName" maxlenght="50" value="<?= $lastName; ?>" required>
                </div>
                <div class="input-item">
                    <input id="street" name="street" type="text" placeholder="Street and Number" value="<?= $street; ?>" required>
                </div>
                <div class="input-item">
                    <input id="zip" name="zip" type="text" placeholder="ZIP - Code" value="<?= $zip; ?>" required>
                    <input id="city" name="city" type="text" placeholder="City" value="<?= $city; ?>" required>
                </div>
                <div class="input-item">
                    <input id="state" name="state" type="text" placeholder="State (optional)" value="<?= $state; ?>">
                    <select id="country" name="country" required>
                        <option value="DE" <?= $country == 'DE' ? ' selected' : '' ?>>Germany</option>
                        <option value="US" <?= $country == 'US' ? ' selected' : '' ?>>United States of America</option>
                        <option value="AT" <?= $country == 'AT' ? ' selected' : '' ?>>Austria</option>
                    </select>
                </div>
                <div class="input-item">
                    <input id="phoneNumber" name="phoneNumber" type="text" placeholder="Phone Number" value="<?= $phoneNumber; ?>" required>
                </div>
                <div class="input-item billing-check">
                    <input id="billingAddress" name="billingAddress" type="checkbox"<?= $billingAddress ? ' checked' : '' ?>>
                    <label for="billingAddress">Different Billing Address</label>
                </div>
                <div class="billing-address<?= $billingAddress ? ' reveal' : '' ?>">
                    <h2>Billing Address</h2>
                    <div class="input-item">
                        <input id="firstName" name="billingFirstName" type="text" placeholder="FirstName" maxlenght="100" value="<?= $billingFirstName; ?>"<?= $billingAddress ? ' required' : '' ?>>
                        <input id="lastName" name="billingLastName" type="text" placeholder="LastName" maxlenght="50" value="<?= $billingLastName; ?>"<?= $billingAddress ? ' required' : '' ?>>
                    </div>
                    <div class="input-item">
                        <input id="street" name="billingStreet" type="text" placeholder="Street and Number" value="<?= $billingStreet; ?>"<?= $billingAddress ? ' required' : '' ?>>
                    </div>
                    <div class="input-item">
                        <input id="zip" name="billingZip" type="text" placeholder="ZIP - Code" value="<?= $billingZip; ?>"<?= $billingAddress ? ' required' : '' ?>>
                        <input id="city" name="billingCity" type="text" placeholder="City" value="<?= $billingCity; ?>"<?= $billingAddress ? ' required' : '' ?>>
                    </div>
                    <div class="input-item">
                        <input class="optional" id="state" name="billingState" type="text" placeholder="State (optional)" value="<?= $billingState; ?>">
                        <select id="country" name="billingCountry" required>
                            <option value="DE" <?= $billingCountry == 'DE' ? ' selected' : '' ?>>Germany</option>
                            <option value="US" <?= $billingCountry == 'US' ? ' selected' : '' ?>>United States of America</option>
                            <option value="AT" <?= $billingCountry == 'AT' ? ' selected' : '' ?>>Austria</option>
                        </select>
                    </div>
                    <div class="input-item">
                        <input id="phoneNumber" name="billingPhoneNumber" type="text" placeholder="Phone Number" value="<?= $billingPhoneNumber; ?>"<?= $billingAddress ? ' required' : '' ?>>
                    </div>
                </div>
                <div class="submit-item">
                    <button>Continue ♥</button>
                </div>
            </form>
        </div>
    </section>
</div>
<script>
    document.querySelector("#checkout #billingAddress").addEventListener('change', function() {
        document.querySelector('#checkout .billing-address').classList.toggle('reveal', this.checked);
        if(this.checked) {
            document.querySelectorAll('#checkout .billing-address input:not(.optional), #checkout .billing-address select:not(.optional)').forEach(function(item) {
                item.setAttribute("required", "1");
            });
        } else {
            document.querySelectorAll('#checkout .billing-address input:not(.optional), #checkout .billing-address select:not(.optional)').forEach(function(item) {
                item.removeAttribute("required");
            });
        }
    });
</script>