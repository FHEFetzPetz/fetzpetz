<form method="post" class="box">
    <h1><?= $type === 'new' ? 'Create User' : 'Edit User' ?></h1>
    <?php if(sizeof($errors) > 0): ?>
        <ul class="errors">
            <?php foreach($errors as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <div class="input-item">
        <input id="firstname" name="firstName" type="text" placeholder="Firstname" maxlenght="100" value="<?= $firstName; ?>" required>
        <input id="lastname" name="lastName" type="text" placeholder="Lastname" maxlenght="50" value="<?= $lastName; ?>" required>
    </div>
    <div class="input-item">
        <input id="email" name="email" type="email" placeholder="E-Mail Address" maxlenght="100" value="<?= $email; ?>" required>
    </div>
    <div class="input-item">
        <input id="password" name="password" type="password" placeholder="Password" minlength="8" maxlength="100"<?= $type === 'new' ? ' required' : '' ?>>
    </div>
    <div class="input-item">
        <input id="repeat-password" name="repeat-password" type="password" placeholder="Repeat Password" minlength="8" maxlength="100"<?= $type === 'new' ? ' required' : '' ?>>
    </div>
    <div class="input-item checkbox">
        <input id="email-verified" name="emailVerified"  type="checkbox"<?= $emailVerified ? ' checked' : '' ?>>
        <label for="email-verified">E-Mail Address verified</label>
    </div>
    <div class="input-item checkbox">
        <input id="active" name="active" type="checkbox"<?= $active ? ' checked' : '' ?>>
        <label for="active">Account activated</label>
    </div>
    <div class="input-item checkbox">
        <input id="administrator" name="administrator" type="checkbox"<?= $administrator ? ' checked' : '' ?>>
        <label for="administrator">Administrator Privileges</label>
    </div>
    <div class="submit-item">
        <button name="submit">Submit</button>
    </div>
    <?php if($type == 'update'): ?>
    <div class="spacer">
        <a class="delete-confirmation" data-question="Confirm User Deletion" href="<?= $this->getPath('/admin/users/delete/' . $user->id) ?>">Delete User</a>
    </div>
    <?php endif; ?>
</form>