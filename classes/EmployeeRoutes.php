<?php



class EmployeeRoutes
{
    private Database $employeeTable;
    private Database $supervisorTable;
    private Database $positionTable;
    private Authentication $authentication;

    public function __construct()
    {
        include __DIR__ . "/../includes/dbConnection.php";
        $this->employeeTable =  new Database($pdo, "zamestnanec", "id");
        $this->supervisorTable =  new Database($pdo, "nadrizeny", "jmeno");
        $this->positionTable = new Database($pdo, "pozice", "id");
        $this->authentication = new Authentication($this->employeeTable);
    }

    public function getRoutes(): array
    {
        $employeeController = new EmployeeController(
            $this->employeeTable,
            $this->supervisorTable,
            $this->positionTable,
            $this->authentication
        );
        $authController = new AuthController($this->authentication);

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
                "login" => true

            ],

            "employee/addNew" => [
                "GET" => [
                    "controller" => $employeeController,
                    "action" => "addNew"
                ],

                "POST" => [
                    "controller" => $employeeController,
                    "action" => "save"
                ],

                "role" => "admin",
                "login" => true

            ],

            "employee/changePassword" => [
                "GET" => [
                    "controller" => $employeeController,
                    "action" => "editPassword"
                ],
                "POST" => [
                    "controller" => $employeeController,
                    "action" => "savePassword"
                ],
                "login" => true
            ],

            "auth/login" => [
                "GET" => [
                    "controller" => $authController,
                    "action" => "showLoginForm"
                ],
                "POST" => [
                    "controller" => $authController,
                    "action" => "login"
                ]
            ],
            "auth/logout" => [
                "GET" => [
                    "controller" => $authController,
                    "action" => "logout"
                ],
                "login" => true
            ]
        ];

        return $rotues;
    }

    public function getAuthentication()
    {
        return $this->authentication;
    }
}
