<h1>přihlášení</h1>
<?php if (isset($error)) : ?>
   <span><?= $error ?></span>
<?php endif ?>

<form action="" method="POST">
   <label for="email">Email</label>
   <input type="text" name="email" id="email">
   <label for="password">Heslo</label>
   <input type="password" name="password" id="password">
   <input type="submit" value="Přihlásit se">
</form>