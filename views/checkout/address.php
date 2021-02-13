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
                    <input id="firstname" name="firstname" type="text" placeholder="Firstname" maxlenght="100" value="<?= $firstName; ?>" required>
                    <input id="lastname" name="lastname" type="text" placeholder="Lastname" maxlenght="50" value="<?= $lastName; ?>" required>
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
                <div class="submit-item">
                    <button>Continue ♥</button>
                </div>
            </form>
        </div>
    </section>
</div>