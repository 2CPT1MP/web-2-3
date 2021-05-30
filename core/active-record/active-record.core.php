<?php require_once("filter.core.php");

abstract class ActiveRecord {
    protected static PDO $databaseObject;
    public static function getDatabaseObject(): PDO { return self::$databaseObject; }

    /**
     * Establishes connection to the database
     */
    public static function connect(): void {

        $dbName = getenv("WEB_DB_NAME");
        $host = getenv("WEB_DB_HOST");
        $username = getenv("WEB_DB_USERNAME");
        $password = getenv("WEB_DB_PASSWORD");

        $dsn = "mysql:dbname=$dbName;host=$host";

        try {
            self::$databaseObject = new PDO(
                $dsn, $username, $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            echo 'Cannot connect to the database' . $e->getMessage();
        }
    }


    public static function find(string $tableName,
                                $syncFunction,
                                $setterFunction,
                                Filter $filter,
                                bool $fetchAll,
                                Order $order = null): IEntity | array | null {
        $syncFunction();
        $filterLimit = $filter->getLimit();
        $sqlLimit = ($filterLimit)? $filterLimit->toSql() : "";
        $order = (!$order)? "" : $order->toSql();

        $query = self::$databaseObject->prepare("
            SELECT * 
            FROM $tableName 
            {$filter->getSqlWhereCondition()}
            
            $order
            $sqlLimit;
        ");

        foreach ($filter->getConditions() as $field => $value)
            $query->bindValue(":$field", $value);
        $query->execute();


        if ($fetchAll) {
            $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);
            $objects = [];
            foreach ($resultSet as $row)
                $objects[] = $setterFunction($row);
            return $objects;
        }
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);

        if (!$resultSet || count($resultSet) < 1)
            return null;

        return $setterFunction($resultSet);
    }
}