<?php
class EmployeeController
{
    private $employeeList;

    public function __construct(EmployeeList $employeeList)
    {
        $this->employeeList = $employeeList;
    }

    private function sortBy(string $orderBy, array $data): array
    {
        $sortArray = array();
        foreach ($data as $person) {
            foreach ($person as $key => $value) {
                if (!isset($sortArray[$key])) {
                    $sortArray[$key] = array();
                }
                $sortArray[$key][] = $value;
            }
        }
        array_multisort($sortArray[$orderBy], SORT_ASC, $data);
        return $data;
    }

    public function home(): array
    {
        $allEmployees = $this->employeeList->getAllEmployees();
        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            $sort = htmlspecialchars($_GET['sort'], ENT_QUOTES, 'UTF-8');
            $allEmployees = $this->sortBy($sort, $allEmployees);
        }
        $title = "Homepage";
        return [
            "title" => $title,
            "template" => "list.html.php",
            "vars" => ["employees" => $allEmployees]
        ];
    }

    public function addNew(): array
    {
        $title = "Nový Zaměstnanec";
        return [
            "title" => $title,
            "template" => "addNew.html.php"
        ];
    }

    public function save(): array
    {
        $employee = $_POST["emp"];
        $sanitizedEmp = [];
        foreach ($employee as $key => $value) {
            $sanitizedEmp[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        $errors = [];
        if (empty($sanitizedEmp['firstname'])) {
            $errors[] = "Zaměstnanec musí mít jméno";
        }
        if (empty($sanitizedEmp['lastname'])) {
            $errors[] = "Zaměstnanec musí mít příjmení";
        }
        if (empty($sanitizedEmp['gender'])) {
            $errors[] = "Zaměstnanec musí mít pohlaví?";
        }
        if (empty($sanitizedEmp['street'])) {
            $errors[] = "Zaměstnanec musí mít Ulici";
        }

        if (empty($sanitizedEmp['city'])) {
            $errors[] = "Zaměstnanec musí mít Obec";
        }
        if (empty($sanitizedEmp['phone'])) {
            $errors[] = "Zaměstnanec musí mít telefon";
        }
        if (empty($sanitizedEmp['zipCode'])) {
            $errors[] = "Zaměstnanec musí mít PSČ";
        }
        if (empty($sanitizedEmp['phone'])) {
            $errors[] = "Zaměstnanec musí mít telefon";
        }
        if (empty($sanitizedEmp['mail'])) {
            $errors[] = "Zaměstnanec musí mít mail";
        }
        if (empty($sanitizedEmp['position'])) {
            $errors[] = "Zaměstnanec musí mít pozici";
        }
        if (($sanitizedEmp['position'] == "dělník") && empty($sanitizedEmp['supervisor'])) {
            $errors[] = "Dělník musí mít nadřízeného";
        }

        if (empty($errors)) {
            $this->employeeList->addNewEmployee($employee);
            header("location: index.php?route=employees/home");
        } else {
            return [
                "template" => "addNew.html.php",
                "title" => "Nový Zaměstnanec haah",
                "vars" => [
                    "errors" => $errors,
                    "employee" => $sanitizedEmp
                ]
            ];
        }
    }
}
