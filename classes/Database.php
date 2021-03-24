<?php
class Database
{
    private $pdo;
    private $table;
    private $primaryKey;
    private $queryString;
    private $parameters;

    public function __construct(PDO $pdo, string $table, string $primaryKey)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->queryString = "";
        $this->parameters = [];
    }


    private function query(string $sql, array $params = []): PDOStatement | bool
    {
        $preparedQuery = $this->pdo->prepare($sql);
        foreach ($params as $name => $value) {
            $preparedQuery->bindValue($name, $value);
        }
        $preparedQuery->execute();
        return $preparedQuery;
    }

    public function queryTest(): PDOStatement | bool
    {
        $preparedQuery = $this->pdo->prepare($this->queryString);
        foreach ($this->parameters as $name => $value) {
            $preparedQuery->bindValue($name, $value);
        }
        $preparedQuery->execute();
        $this->queryString = '';
        $this->parameters = [];
        return $preparedQuery;
    }

    public function findById(string $id): array
    {
        $query = "SELECT * from " . $this->table . " WHERE " . $this->primaryKey . " = :id";
        return $this->query($query, [":id" => $id])->fetch();
    }

    public function findAllAndReturnData(string $orderBy = '', string $order = "ASC"): array
    {
        $query = "select * FROM " . $this->table;
        if ($orderBy != '') {
            $query .= " ORDER BY " . $orderBy . " " . $order;
        }
        return $this->query($query)->fetchAll();
    }

    public function findAll(): Database
    {
        $this->queryString .= "select * FROM " . $this->table . " ";
        return $this;
    }

    public function countAll(): Database
    {
        $this->queryString .= "select count(*) FROM " . $this->table . " ";
        return $this;
    }

    //metoda umožnňující řetězení metod, tak aby šlo např. vytvořit dotaz ... where ... order by ...
    public function sort(string $orderBy, string $order = "ASC"): Database
    {
        if ($orderBy == '') {
            return $this;
        }
        $this->queryString .= " ORDER BY " . $orderBy . " " . $order . " ";
        return $this;
    }

    //metoda umožnňující řetězení metod, tak aby šlo např. vytvořit dotaz ... where ... order by ...
    public function where($field, $value): Database
    {
        if ($value == '') {
            //vrácí vše proto, aby správně fungovala metoda and()
            $this->queryString .= " WHERE 1=1 ";
            return $this;
        }
        $this->queryString .= " WHERE " . $field . " = :value";
        $this->parameters[":value"] = $value;
        return $this;
    }

    public function and($field, $value): Database
    {
        if ($value == '') {
            return $this;
        }
        $this->queryString .= " AND " . $field . " = :andParam";
        $this->parameters[":andParam"] = $value;
        return $this;
    }

    public function findAllWhere(string $field, string $value): array
    {

        $query = "select * FROM " . $this->table;
        $query .= " WHERE " . $field . " = :value";
        $parameter = [":value" => $value];
        return $this->query($query, $parameter)->fetchAll();
    }

    public function insert(array $data): void
    {
        $query = "INSERT INTO " . $this->table . "(";
        foreach ($data as $key => $value) {
            $query .=  $key . ',';
        }
        // odstraneni posledni prebytecne carky
        $query = rtrim($query, ',');
        $query .= ") VALUES (";
        foreach ($data as $key => $value) {
            $query .= ':' . $key . ',';
        }
        $query = rtrim($query, ',');
        $query .= ')';
        $this->query($query, $data);
    }

    public function update($fields): void
    {
        $query = 'UPDATE ' . $this->table . ' SET ';
        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '` = :' . $key . ',';
        }
        $query = rtrim($query, ',');
        $query .= ' WHERE `' . $this->primaryKey . '` = :id';

        $fields[':id'] = $fields['id'];

        $this->query($query, $fields);
    }

    public function limit($offset, $results)
    {
        $this->queryString .= " LIMIT " . $offset . ", " . $results;
        return $this;
    }

    public function count()
    {
        return $this->query("SELECT count(*) FROM " . $this->table)->fetch();
    }


    public function save(array $data): void
    {
        //při vytvoření nového zaměstnance je id nastaveno ve formě na prázdný string
        //přenastavení na null, aby došlo v db k auto increment
        if ($data['id'] == '') {
            $data['id'] = null;
            $this->insert($data);
        } else {
            $this->update($data);
        }
    }
}
