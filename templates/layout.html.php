<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <header>
        <h1>Domácí úkol</h1>
    </header>
    <nav>
        <ul>
            <?php if ($isLoggedIn) : ?>
                <li><a href="index.php">Domů</a></li>
                <li><a href="index.php?route=auth/logout">Odhlásit</a></li>
                <?php if ($user["pozice"] == "admin") : ?>
                    <li><a href="index.php?route=employee/addNew">Nový zaměstnanec</a></li>
                <?php endif; ?>
                <li><a href="index.php?route=employee/changePassword">Změna Hesla</a></li>
            <?php else : ?>
                <li><a href="index.php?route=auth/login">Přihlásit se</a></li>
                <li><a href="index.php">Domů</a></li>
            <?php endif ?>
        </ul>
    </nav>
    <main>
        <?= $output ?>
    </main>
</body>

</html>