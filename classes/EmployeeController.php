<?php
class EmployeeController
{
    private $employeeTable;
    private $supervisorTable;
    private $positionTable;
    private $authentication;
    private $errorTable;
    const EMPLOYEES_PER_PAGE = 10;

    public function __construct(
        Database $employeeTable,
        Database $supervisorTable,
        Database $positionTable,
        Authentication $authentication
    ) {
        $this->employeeTable = $employeeTable;
        $this->supervisorTable = $supervisorTable;
        $this->positionTable = $positionTable;
        $this->authentication = $authentication;
        $this->errorTable = [
            "jmeno" => "Zaměstnanec musí mít jméno",
            "prijmeni" => "Zaměstnanec musí mít příjmení",
            "pohlavi" => "Zaměstnanec musí mít pohlaví?",
            "ulice" => "Zaměstnanec musí mít Ulici",
            "obec" => "Zaměstnanec musí mít Obec",
            "telefon" => "Zaměstnanec musí mít telefon",
            "psc" => "Zaměstnanec musí mít PSČ",
            "email" => "Zaměstnanec musí mít mail",
            "pozice" => "Zaměstnanec musí mít pozici",
            "vychoziHeslo" => "Jako administrátor musíte nastavit výchozí heslo",
            "heslo" => "heslo nemůže být prázdné",
            "hesloPotvrzeni" => "Prosím, potvrtďte Vaše heslo",






        ];
    }


    public function home(): array
    {
        $page = $_GET['page'] ?? 1;
        $page = htmlspecialchars($page, ENT_QUOTES, 'UTF-8');
        $supervisors = $this->supervisorTable->findAllAndReturnData();
        $positions = $this->positionTable->findAllAndReturnData();
        $offset = ($page - 1)  * EmployeeController::EMPLOYEES_PER_PAGE;
        $user = $this->authentication->getUser();
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

        $employees = array_map(function ($employee) use ($user) {
            return $this->addPermmissionForRole($employee, $user);
        }, $employees);

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
                "positions" => $positions,
                "user" => $user
            ]
        ];
    }

    public function editOrAddNew(): array
    {
        $user = $this->authentication->getUser();
        $employee = null;
        $supervisors = $this->supervisorTable->findAllAndReturnData();
        $positions = $this->positionTable->findAllAndReturnData();
        if (isset($_GET['id'])) {
            $employee = $this->employeeTable->findById($_GET['id']);
            $employee = $this->addPermmissionForRole($employee, $user);
        }
        $title = "Editace Zaměstnance";
        return [
            "title" => $title,
            "template" => "editOrAddNew.html.php",
            "vars" => [
                "employee" => $employee,
                "supervisors" => $supervisors,
                "positions" => $positions,
                "user" => $user
            ]
        ];
    }

    public function addNew(): array
    {

        $user = $this->authentication->getUser();
        $employee = null;
        $supervisors = $this->supervisorTable->findAllAndReturnData();
        $positions = $this->positionTable->findAllAndReturnData();
        $title = "Přidání nového zaměstnance";
        return [
            "title" => $title,
            "template" => "addNew.html.php",
            "vars" => [
                "employee" => $employee,
                "supervisors" => $supervisors,
                "positions" => $positions,
                "user" => $user
            ]
        ];
    }
    // metoda umožňuje editaci i založení nového zaměstnance
    // vyhneme se tam vyvoření dou víceměně stejných metod a templátů

    //po rozšíření funkcionalit bylo nutné přidat metdody zvlášť pro editace a pro ukládání nových zaměstnanců

    public function save(): array
    {
        $positions = $this->positionTable->findAllAndReturnData();
        $user = $this->authentication->getUser();
        $employee = $_POST["emp"];
        echo $employee["pozice"];
        echo $employee["nadrizeny"];
        $sanitizedEmp = [];
        foreach ($employee as $key => $value) {
            $sanitizedEmp[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        $errors = [];
        foreach ($sanitizedEmp as $key => $value) {
            if (empty($value) && isset($this->errorTable[$key])) {
                $errors[] = $this->errorTable[$key];
            }
        }

        if ($sanitizedEmp["pozice"] == "dělník" && empty($sanitizedEmp["nadrizeny"])) {
            $errors[] = "pro pozici dělník musíte musí mít nadřízeného";
        }
        if (empty($errors)) {
            if ($sanitizedEmp['nadrizeny'] == '') {
                $sanitizedEmp['nadrizeny'] = null;
            }

            //pokud byl uživatel nově zakládán, tak by byla chyba zachycena dříve
            // pokud v tomto bodě neexistuje heslo, tak to znamená, že je uživatel editiván
            if (!empty($sanitizedEmp["heslo"])) {
                $sanitizedEmp['heslo'] = password_hash($sanitizedEmp['heslo'], PASSWORD_DEFAULT);
            }
            $this->employeeTable->save($sanitizedEmp);

            if ($sanitizedEmp["pozice"] == "mistr") {
                $newEmployee = $this->employeeTable->findOne("email", $sanitizedEmp["email"]);
                $id = $newEmployee["id"];
                $firstname = $newEmployee["jmeno"];
                $lastname = $newEmployee["prijmeni"];
                $fullname = $firstname . " " . $lastname;
                $this->supervisorTable->save(["jmeno" => $fullname, "id_zamestnance" => $id, "id" => null]);
            }
            header("location: index.php?route=employees/home");
        } else {
            $supervisors = $this->supervisorTable->findAllAndReturnData();
            $template = $sanitizedEmp["id"] != null ? "editOrAddNew.html.php" : "addNew.html.php";
            return [
                "template" => $template,
                "title" => "Nový Zaměstnanec haah",
                "vars" => [
                    "errors" => $errors,
                    "employee" => $sanitizedEmp,
                    "supervisors" => $supervisors,
                    "user" => $user,
                    "positions" => $positions
                ]
            ];
        }
    }

    public function yourEmployees()
    {
    }

    public function editPassword(): array
    {

        $title = "Změna hesla";
        $user = $this->authentication->getUser();

        return [
            "title" => $title,
            "template" => "changePassword.html.php",
            "vars" => [
                "user" => $user
            ]
        ];
    }

    public function savePassword()
    {
        $user = $this->authentication->getUser();
        //uživatel nemůže měnit heslo bez přihlášení
        $errors = [];
        if (empty($user)) {
            return;
        }

        $oldPassword = $_POST["oldPassword"] ?? '';
        if (!password_verify($oldPassword, $user["heslo"])) {
            $errors[] = "Zadali jste špatné původní heslo";
        }
        if (empty($_POST["newPassword"])) {
            $errors[] = "Nové heslo nemůže být prázdné";
        }
        if (empty($_POST["passwordConfirm"])) {
            $errors[] = "Potvzrení heslo nemůže být prázdné";
        }
        if (
            !empty($_POST["newPassword"]) && !empty($_POST["newPassword"]) &&
            $_POST["newPassword"] != $_POST["passwordConfirm"]
        ) {
            $errors[] = "Zadali jste odlišná hesla";
        }

        if (empty($errors)) {
            $password = htmlspecialchars($_POST["newPassword"], ENT_QUOTES);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->employeeTable->save(["id" => $user["id"], "heslo" => $hashedPassword]);
            $message = "Heslo úspěšně změněno";
            $title = "success";
            return [
                "title" => $title,
                "template" => "success.html.php",
                "vars" => [
                    "message" => $message
                ]
            ];
        } else {
            $title = "Změna hesla";
            return [
                "title" => $title,
                "template" => "changePassword.html.php",
                "vars" => [
                    "user" => $user,
                    "errors" => $errors
                ]
            ];
        }
    }

    private function addPermmissionForRole($employee, $user)
    {
        if (!$user) {
            $employee["editable"] = false;
            return $employee;
        }
        if ($user["id"] == $employee["id"]) {
            $employee["editable"] = true;
            $employee["editableFields"] = ["jmeno", "prijmeni", "pohlavi", "obec", "telefon", "email", "ulice", "psc",];
        } else if ($user["pozice"] == "admin") {
            $employee["editable"] = true;
            $employee["editableFields"] = ["pozice", "nadrizeny"];
        } else if ($user["pozice"] == "mistr" && $user["jmeno"] . " " . $user["prijmeni"] == $employee["nadrizeny"]) {
            $employee["editable"] = true;
            $employee["editableFields"] = ["pozice"];
        } else {
            $employee["editable"] = false;
        }

        return $employee;
    }
}
