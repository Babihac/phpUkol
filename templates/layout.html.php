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
            <li><a href="index.php?route=employee/edit">Nový zaměstnanec</a></li>
        </ul>
    </nav>
    <main>
        <?= $output ?>
    </main>
</body>

</html>