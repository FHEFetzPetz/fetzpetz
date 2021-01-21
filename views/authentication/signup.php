<div id="authentication">
    <section class="signup-view view">
        <h1 style="text-align: center;">Sign Up<br></h1>
        <form method="post" class="form">
            <?php if(sizeof($errors) > 0): ?>
            <ul class="errors">
                <?php foreach($errors as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <div class="input-item">
                <input id="firstname" name="firstname" type="text" placeholder="Firstname" maxlenght="100" value="<?= $firstName; ?>" required>
                <input id="lastname" name="lastname" type="text" placeholder="Lastname" maxlenght="50" value="<?= $lastName; ?>" required>
            </div>
            <div class="input-item">
                <input id="email" name="email" type="email" placeholder="E-Mail Address" value="<?= $email; ?>" required>
            </div>
            <div class="input-item">
                <input id="password" name="password" type="password" placeholder="Password" required>
            </div>
            <div class="input-item">
                <input id="repeat-password" name="repeat-password" type="password" placeholder="Repeat Password" required>
            </div>
            <div class="submit-item">
                <button>Sign up! ♥</button>
            </div>
        </form>
    </section>
</div>