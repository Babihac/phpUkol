<?php



class EmployeeRoutes
{
    private EmployeeList $employeeList;

    public function __construct(EmployeeList $employeeList)
    {
        $this->employeeList = $employeeList;
    }

    public function getRoutes(): array
    {
        $employeeController = new EmployeeController($this->employeeList);

        $rotues = [
            "employees/home" => [
                "GET" => [
                    "controller" => $employeeController,
                    "action" => "home"
                ]
            ],

            "employee/new" => [
                "GET" => [
                    "controller" => $employeeController,
                    "action" => "addNew"
                ],
                "POST" => [
                    "controller" => $employeeController,
                    "action" => "save"
                ]
            ]
        ];

        return $rotues;
    }
}
