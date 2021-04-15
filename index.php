<?php
session_start();
include __DIR__ . "/./includes/autoload.php";
$route = $_GET['route'] ?? 'employees/home';
$entryPoint = new EntryPoint($route, $_SERVER['REQUEST_METHOD'], new EmployeeRoutes());

$entryPoint->run();
