<?php
include __DIR__ . "/includes/dbConnection.php";
$employeeDb = new Database($pdo, 'zamestnanec');
$employeeList = new EmployeeList(__DIR__ . '/adresar.csv');
$arr = $employeeList->getAllEmployees();
try {
    foreach ($arr as $zam) {
        echo $employeeDb->insert($zam);
    }
    //unlink(__DIR__ . "/adresar.csv");
} catch (PDOException $e) {
    echo $e;
}
