<?php



class EmployeeRoutes
{
    private Database $employeeTable;
    private Database $supervisorTable;
    private Database $positionTable;

    public function __construct()
    {
        include __DIR__ . "/../includes/dbConnection.php";
        $this->employeeTable =  new Database($pdo, "zamestnanec", "id");
        $this->supervisorTable =  new Database($pdo, "nadrizeny", "jmeno");
        $this->positionTable = new Database($pdo, "pozice", "id");
    }

    public function getRoutes(): array
    {
        $employeeController = new EmployeeController($this->employeeTable, $this->supervisorTable, $this->positionTable);

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
