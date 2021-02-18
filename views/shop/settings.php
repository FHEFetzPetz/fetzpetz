<?php $user = $this->getUser(); ?>
<div class="container">
    <section id="shop">
        <div class="row">
            <?php $this->renderComponent("components/profileSidebar.php"); ?>
            <div id="content">
                <form method="POST" class="box">
                    <h2>Personal Information</h2>
                    <div class="input-item">
                        <input id="firstName" name="firstName" type="text" placeholder="FirstName" maxlenght="100" value="<?= $user->firstname; ?>" required>
                        <input id="lastName" name="lastName" type="text" placeholder="LastName" maxlenght="50" value="<?= $user->lastname; ?>" required>
                    </div>
                    <div class="submit-item">
                        <button name="updatePersonal">Update ♥</button>
                    </div>
                </form>
                <form method="POST" class="box">
                    <h2>E-Mail Address</h2>
                    <div class="input-item">
                        <input id="email" name="email" type="email" placeholder="New E-Mail Address" maxlenght="100" required>
                    </div>
                    <div class="submit-item">
                        <button name="updateEmail">Update ♥</button>
                    </div>
                </form>
                <form method="POST" class="box">
                    <h2>Password</h2>
                    <div class="input-item">
                        <input id="oldPassword" name="oldPassword" type="password" placeholder="Old Password" maxlenght="100" required>
                    </div>
                    <div class="input-item">
                        <input id="newPassword" name="newPassword" type="password" placeholder="New Password" minlength="8" maxlenght="100" required>
                        <input id="newPassword2" name="newPassword2" type="password" placeholder="Repeat Password" minlength="8" maxlenght="100" required>
                    </div>
                    <div class="submit-item">
                        <button name="updatePassword">Update ♥</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>