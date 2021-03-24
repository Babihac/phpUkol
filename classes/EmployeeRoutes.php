<?php



class EmployeeRoutes
{
    private Database $employeeTable;
    private Database $supervisorTable;

    public function __construct()
    {
        include __DIR__ . "/../includes/dbConnection.php";
        $this->employeeTable =  new Database($pdo, "zamestnanec", "id");
        $this->supervisorTable =  new Database($pdo, "nadrizeny", "jmeno");
    }

    public function getRoutes(): array
    {
        $employeeController = new EmployeeController($this->employeeTable, $this->supervisorTable);

        $rotues = [
            "employees/home" => [
                "GET" => [
                    "controller" => $employeeController,
                    "action" => "home"
                ]
            ],

            "employee/edit" => [
                "GET" => [
                    "controller" => $employeeController,
                    "action" => "editOrAddNew"
                ],
                "POST" => [
                    "controller" => $employeeController,
                    "action" => "save"
                ],
            ]
        ];

        return $rotues;
    }
}
