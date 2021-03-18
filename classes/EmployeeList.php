<?php


class EmployeeList
{
    private string $filename;

    public function __construct(string $fileName)
    {
        $this->filename = $fileName;
    }

    public function getAllEmployees(): array
    {
        $arr = [];
        $file = fopen($this->filename, 'r');
        $header =  fgetcsv($file, 0, ";");
        while (($line = fgetcsv($file, 0, ";")) !== FALSE) {
            $entry = array_combine($header, $line);
            $arr[] = $entry;
        }
        fclose($file);
        return $arr;
    }

    public function addNewEmployee(array $employee): void
    {
        $handle = fopen($this->filename, "a");
        fputcsv($handle, $employee, ";");
        fclose($handle);
    }
}
