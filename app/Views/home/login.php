<form method="POST">
    <h2>Авторизоваться</h2>
    <label for="inputUsername" class="sr-only">Username:</label>
    <input name="username" id="inputUsername" class="form-control" placeholder="Username" required autofocus
           value="<?php echo $username; ?>">
    <label for="inputPassword" class="sr-only">Password</label>
    <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>

    <?php if (!empty($error)) { ?>
        <?php echo $error; ?>
    <?php } ?>

    <button name="action">Войти</button>
</form>
