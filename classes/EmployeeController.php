<?php
class EmployeeController
{
    private $employeeTable;
    private $supervisorTable;
    private $positionTable;
    const EMPLOYEES_PER_PAGE = 10;

    public function __construct(Database $employeeTable, Database $supervisorTable, Database $positionTable)
    {
        $this->employeeTable = $employeeTable;
        $this->supervisorTable = $supervisorTable;
        $this->positionTable = $positionTable;
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
        $page = $_GET['page'] ?? 1;
        $page = htmlspecialchars($page, ENT_QUOTES, 'UTF-8');
        $supervisors = $this->supervisorTable->findAllAndReturnData();
        $positions = $this->positionTable->findAllAndReturnData();
        $offset = ($page - 1)  * EmployeeController::EMPLOYEES_PER_PAGE;
        //prázndý string znamemá, že se nebude podle daného parametru třídit, nebo řadit
        $supervisor = '';
        $position = '';
        $sort = '';
        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            $sort = htmlspecialchars($_GET['sort'], ENT_QUOTES, 'UTF-8');
        }
        // } else {
        //     $allEmployees = $this->employeeTable->findAll();
        // }
        if (isset($_GET['supervisor'])  && !empty($_GET['supervisor'])) {
            $supervisor = htmlspecialchars($_GET['supervisor'], ENT_QUOTES, 'UTF-8');
        }

        if (isset($_GET['position'])  && !empty($_GET['position'])) {
            $position = htmlspecialchars($_GET['position'], ENT_QUOTES, 'UTF-8');
        }

        $employees = $this->employeeTable
            ->findAll()
            ->where("nadrizeny", $supervisor)
            ->and("pozice", $position)
            ->sort($sort)
            ->limit($offset, EmployeeController::EMPLOYEES_PER_PAGE)
            ->queryTest()
            ->fetchAll();

        $count = $this->employeeTable
            ->countAll()
            ->where("nadrizeny", $supervisor)
            ->and("pozice", $position)
            ->queryTest()
            ->fetch()[0];
        $maxPage = ceil($count / EmployeeController::EMPLOYEES_PER_PAGE);
        $title = "Homepage";
        return [
            "title" => $title,
            "template" => "list.html.php",
            "vars" => [
                "employees" => $employees,
                "supervisors" => $supervisors,
                "page" => $page,
                "maxPage" => $maxPage,
                "positions" => $positions
            ]
        ];
    }

    public function editOrAddNew(): array
    {
        $employee = null;
        $supervisors = $this->supervisorTable->findAllAndReturnData();
        if (isset($_GET['id'])) {
            $employee = $this->employeeTable->findById($_GET['id']);
        }
        $title = "Editace Zaměstnance";
        return [
            "title" => $title,
            "template" => "editOrAddNew.html.php",
            "vars" => [
                "employee" => $employee,
                "supervisors" => $supervisors
            ]
        ];
    }
    // metoda umožňuje editaci i založení nového zaměstnance
    // vyhneme se tam vyvoření dou víceměně stejných metod a templátů
    public function save(): array
    {
        $employee = $_POST["emp"];
        $sanitizedEmp = [];
        foreach ($employee as $key => $value) {
            $sanitizedEmp[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        $errors = [];
        if (empty($sanitizedEmp['jmeno'])) {
            $errors[] = "Zaměstnanec musí mít jméno";
        }
        if (empty($sanitizedEmp['prijmeni'])) {
            $errors[] = "Zaměstnanec musí mít příjmení";
        }
        if (empty($sanitizedEmp['pohlavi'])) {
            $errors[] = "Zaměstnanec musí mít pohlaví?";
        }
        if (empty($sanitizedEmp['ulice'])) {
            $errors[] = "Zaměstnanec musí mít Ulici";
        }

        if (empty($sanitizedEmp['obec'])) {
            $errors[] = "Zaměstnanec musí mít Obec";
        }
        if (empty($sanitizedEmp['telefon'])) {
            $errors[] = "Zaměstnanec musí mít telefon";
        }
        if (empty($sanitizedEmp['psc'])) {
            $errors[] = "Zaměstnanec musí mít PSČ";
        }
        if (empty($sanitizedEmp['email'])) {
            $errors[] = "Zaměstnanec musí mít mail";
        }
        if (empty($sanitizedEmp['pozice'])) {
            $errors[] = "Zaměstnanec musí mít pozici";
        }
        if (($sanitizedEmp['pozice'] == "dělník") && empty($sanitizedEmp['nadrizeny'])) {
            $errors[] = "Dělník musí mít nadřízeného";
        }

        if (empty($errors)) {
            $this->employeeTable->save($sanitizedEmp);
            header("location: index.php?route=employees/home");
        } else {
            $supervisors = $this->supervisorTable->findAllAndReturnData();
            return [
                "template" => "editOrAddNew.html.php",
                "title" => "Nový Zaměstnanec haah",
                "vars" => [
                    "errors" => $errors,
                    "employee" => $sanitizedEmp,
                    "supervisors" => $supervisors
                ]
            ];
        }
    }
}
