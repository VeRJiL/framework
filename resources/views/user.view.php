<?php require "partials/header.php"; ?>

<h1> Home Page </h1>

<table>
    <thead>
    <tr>
        <th>First Name:</th>
        <th>Password</th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user->user_name; ?></td>
            <td><?= $user->password; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<br>

<br>



<form method="POST" action="users/store">
	UserName: <input type="text" name="user_name">
	<br>
	<br>
	Password: <input type="text" name="password">
	<br>
	<br>
	<input type="submit" name="submit" class="btn-success">
</form>

<?php require "partials/footer.php"; ?>