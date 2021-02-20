<div class="box">
    <div class="title-row">
        <div class="title">
            <h1>Users</h1>
            <h2><?= sizeof($users) ?> user(s) in total</h2>
        </div>
        <a href="<?= $this->getPath('/admin/users/new') ?>" class="button">
            Create User
        </a>
    </div>
    <table cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>E-Mail Address</th>
                <th>Verified</th>
                <th>Active</th>
                <th>Administrator</th>
                <th>Created at</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($users as $user): ?>
            <tr>
                <td><span class="mobile-field">ID: </span></span><a href="<?= $this->getPath('/admin/users/edit/' . $user->id) ?>"><?= $user->id ?></a></td>
                <td><span class="mobile-field">Name: </span><?= $user->firstname . ' ' . $user->lastname ?></td>
                <td><span class="mobile-field">E-Mail Address: </span><?= $user->email ?></td>
                <td><span class="mobile-field">Verified: </span><?= $user->email_verified ? 'Yes' : 'No' ?></td>
                <td><span class="mobile-field">Active: </span><?= $user->active ? 'Yes' : 'No' ?></td>
                <td><span class="mobile-field">Administrator: </span><?= isset($administrators[$user->id]) ? 'Yes' : 'No' ?></td>
                <td><span class="mobile-field">Created at: </span><?= $user->created_at->format('H:i d.m.Y') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>