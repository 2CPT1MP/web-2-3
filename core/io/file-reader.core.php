<?php

abstract class FileReader {
    protected string $fileName;

    public function __construct(string $fileName) {
        $this->fileName = $fileName;
    }

    public abstract function read();
}

class INCFileReader extends FileReader {

    public function read() {
        // TODO: Implement read() method.
    }
}

class CSVFileReader extends FileReader {

    public function read(): array {
        $rows = array_map('str_getcsv', file($this->fileName));
        $header = array_shift($rows);
        $csv = [];

        foreach($rows as $row)
            if (count($header) === count($row))
                $csv[] = array_combine($header, $row);

        return $csv;
    }
}