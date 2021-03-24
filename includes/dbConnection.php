<?php
include __DIR__ . "/../dbConfig.php";
$pdo  = new PDO(
    'mysql:host=localhost;dbname=ukol; charset=utf8',
    $DB_USERNAME,
    $DB_PASSWORD
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
