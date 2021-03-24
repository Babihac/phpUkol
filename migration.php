<?php
include __DIR__ . "/includes/dbConnection.php";

try {
    $employeeDb = new Database($pdo, 'zamestnanec2', "id");
    $employeeList = new EmployeeList(__DIR__ . '/adresar.csv');
    $arr = $employeeList->getAllEmployees();
    foreach ($arr as $zam) {
        $employeeDb->insert($zam);
    }
    //unlink(__DIR__ . "/./adresar.csv");
    //unlink(__DIR__ . "/adresar.csv");
} catch (PDOException $e) {
    echo $e;
}
