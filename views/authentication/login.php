<h1>Demo Login</h1>
<?php if($authenticatedUser != null): ?>
<h2>You are authenticated as <?= $authenticatedUser->__get("firstname") ?></h2>
<?php endif; ?>

<?php

if ($authenticatedUser != null): ?>
<form action="<?= $this->getPath("/logout") ?>">
    <input name="submit" type="submit" value="Click here to logout">
</form>
<?php elseif($user != null): ?>
<form method="post">
    <input name="submit" type="submit" value="Click here to authenticate as <?= $user->__get("firstname"); ?>">
</form>
<?php endif; ?>
<br>
<form action="<?= $this->getPath("/security") ?>">
    <input name="submit" type="submit" value="Visit secure page">
</form>