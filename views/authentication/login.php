<div id="authentication">
    <section class="signup-info view">
        <h2 style="text-align: center;">Wanna join the party?<br></h2>
        <form method='GET' action='signup' class="form">
            <div class="submit-item">
                <button>Sign Up now! ♥</button>
            </div>
        </form>
    </section>

    <div class="divider"></div>
    <section class="login-view view">
        <h1 style="text-align: center;">Login<br></h1>
        <form method="post" class="form">
            <?php if (sizeof($errors) > 0) : ?>
                <ul class="errors">
                    <?php foreach ($errors as $error) : ?>
                        <li><?= $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <div class="input-item">
                <input id="email" name="email" type="email" placeholder="E-Mail Address">
            </div>
            <div class="input-item">
                <input id="password" name="password" type="password" placeholder="Password">
            </div>
            <div class="submit-item">
                <button>Login! ♥</button>
            </div>
        </form>
    </section>
</div>