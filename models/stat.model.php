<?php
require_once(__DIR__ . '/../core/active-record/entity.core.php');
require_once(__DIR__ . '/../core/active-record/active-record.core.php');
require_once(__DIR__ . '/../core/active-record/filter.core.php');
require_once(__DIR__ . '/../core/active-record/order.core.php');

class Stat implements IEntity {
    private int | null $id = null;
    private string $timestamp, $uri, $ip, $host, $browser;

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp(string $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getBrowser(): string
    {
        return $this->browser;
    }

    /**
     * @param string $browser
     */
    public function setBrowser(string $browser): void
    {
        $this->browser = $browser;
    }



    private function createNew(): bool {
        if (!isset($this->timestamp))
            $this->timestamp = date('Y-m-d H:i:s');

        if (!isset($this->host))
            $this->host = "Не найден";

        $query = ActiveRecord::getDatabaseObject()->prepare("
                INSERT INTO Stat(timestamp, uri, ip, host, browser) 
                VALUES(:timestamp, :uri, :ip, :host, :browser);
        ");

        $this->bindValuesToQuery($query, false);
        $res = $query->execute();

        $this->setId(ActiveRecord::getDatabaseObject()->lastInsertId());
        return $res;
    }


    private function updateExisting(): bool {
        if (!isset($this->timestamp))
            $this->timestamp = date('Y-m-d H:i:s');

        if (!isset($this->host))
            $this->host = "Не найден";

        $query = ActiveRecord::getDatabaseObject()->prepare("
            UPDATE Stat 
            SET timestamp = :timestamp ,
                uri = :uri, 
                ip = :ip, 
                host = :host,
                browser = :browser
            WHERE id = :id;
        ");

        $this->bindValuesToQuery($query);
        return $query->execute();
    }

    public function save(): bool {
        self::sync();
        return ($this->id)? $this->updateExisting() : $this->createNew();
    }

    function delete(): bool {
        self::sync();
        if (!$this->id) return false;

        $query = ActiveRecord::getDatabaseObject()->prepare("
            DELETE FROM Stat
            WHERE id = :id;
        ");
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    static function findById(int $id): array {
        self::sync();
        $idFilter = new Filter();
        $idFilter->addCondition("id", $id);
        return self::find($idFilter);
    }

    /** @return Stat[] */
    static function findAll(): array {
        self::sync();
        return self::find(new Filter());
    }

    static function deleteAll(): bool {
        $success = true;
        foreach(Stat::findAll() as $stat) {
            $result = $stat->delete();
            if (!$result)
                $success = false;
        }
        return $success;
    }

    public static function getPageCount(int $recordsPerPage): int {
        self::sync();
        $query = "SELECT COUNT(*) FROM Stat;";
        $statement = ActiveRecord::getDatabaseObject()->query($query);
        if (!$statement)
            return 0;
        $recordCount = $statement->fetch(PDO::FETCH_NUM)[0];

        return ceil($recordCount / $recordsPerPage);
    }

    public static function getCount(): int {
        self::sync();
        $query = "SELECT COUNT(*) FROM Stat;";
        $statement = ActiveRecord::getDatabaseObject()->query($query);
        if (!$statement)
            return 0;
        return $statement->fetch(PDO::FETCH_NUM)[0];
    }

    public static function setRows($row): Stat {
        $newObject = new Stat();
        $newObject->setId($row["id"]);
        $newObject->setTimestamp($row["timestamp"]);
        $newObject->setUri($row["uri"]);
        $newObject->setIp($row['ip']);
        $newObject->setHost($row["host"]);
        $newObject->setBrowser($row["browser"]);
        return $newObject;
    }

    private function bindValuesToQuery($query, bool $includeId = true): void {
        if ($includeId)
            $query->bindParam(":id", $this->id);
        $query->bindParam(":timestamp", $this->timestamp);
        $query->bindParam(":uri", $this->uri);
        $query->bindParam(":ip", $this->ip);
        $query->bindParam(":host", $this->host);
        $query->bindParam(":browser", $this->browser);
    }

    static function findAllForPage(int $page, int $recordsPerPage): array {
        self::sync();
        $filter = new Filter();
        $filter->setLimit(new Limit($page, $recordsPerPage));
        return self::find($filter);
    }

    public static function sync() {
        $query = ActiveRecord::getDatabaseObject()->prepare("
            CREATE TABLE IF NOT EXISTS Stat(
                id INTEGER PRIMARY KEY AUTO_INCREMENT,
                timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                uri VARCHAR(500) NOT NULL,
                ip VARCHAR(500) NOT NULL,
                host VARCHAR(500) NOT NULL DEFAULT 'Не найден',
                browser VARCHAR(500) NOT NULL
            );
        ");
        $query->execute();
    }

    static function find(Filter $filter, bool $fetchAll = true): Stat | array {
        return ActiveRecord::find("Stat",
            "Stat::sync",
            "Stat::setRows",
            $filter,
            $fetchAll,
            new DescendingOrder("timestamp")
        );
    }
}