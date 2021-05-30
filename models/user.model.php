<?php
require_once(__DIR__ . '/../core/active-record/entity.core.php');
require_once(__DIR__ . '/../core/active-record/active-record.core.php');
require_once(__DIR__ . '/../core/active-record/filter.core.php');
require_once(__DIR__ . '/../core/active-record/order.core.php');

class User implements IEntity {
    private int | null $id = null;
    private string $name, $email, $login, $password;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    private function createNew(): bool {
        $query = ActiveRecord::getDatabaseObject()->prepare("
                INSERT INTO User(name, email, login, password) 
                VALUES(:name, :email, :login, :password);
        ");

        $this->bindValuesToQuery($query, false);
        $res = $query->execute();

        $this->setId(ActiveRecord::getDatabaseObject()->lastInsertId());
        return $res;
    }

    private function updateExisting(): bool {
        $query = ActiveRecord::getDatabaseObject()->prepare("
            UPDATE User 
            SET name = :name ,
                email = :email, 
                login = :login, 
                password = :password,
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
            DELETE FROM User
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

    /** @return User[] */
    static function findAll(): array {
        self::sync();
        return self::find(new Filter());
    }

    static function deleteAll(): bool {
        $success = true;
        foreach(User::findAll() as $stat) {
            $result = $stat->delete();
            if (!$result)
                $success = false;
        }
        return $success;
    }

    public static function getPageCount(int $recordsPerPage): int {
        self::sync();
        $query = "SELECT COUNT(*) FROM User;";
        $statement = ActiveRecord::getDatabaseObject()->query($query);
        if (!$statement)
            return 0;
        $recordCount = $statement->fetch(PDO::FETCH_NUM)[0];

        return ceil($recordCount / $recordsPerPage);
    }

    public static function getCount(): int {
        self::sync();
        $query = "SELECT COUNT(*) FROM User;";
        $statement = ActiveRecord::getDatabaseObject()->query($query);
        if (!$statement)
            return 0;
        return $statement->fetch(PDO::FETCH_NUM)[0];
    }

    public static function setRows($row): User {
        $newObject = new User();
        $newObject->setId($row["id"]);
        $newObject->setName($row["name"]);
        $newObject->setEmail($row["email"]);
        $newObject->setLogin($row['login']);
        $newObject->setPassword($row["password"]);
        return $newObject;
    }

    private function bindValuesToQuery($query, bool $includeId = true): void {
        if ($includeId)
            $query->bindParam(":id", $this->id);
        $query->bindParam(":name", $this->name);
        $query->bindParam(":email", $this->email);
        $query->bindParam(":login", $this->login);
        $query->bindParam(":password", $this->password);
    }

    static function findAllForPage(int $page, int $recordsPerPage): array {
        self::sync();
        $filter = new Filter();
        $filter->setLimit(new Limit($page, $recordsPerPage));
        return self::find($filter);
    }

    public static function sync() {
        $query = ActiveRecord::getDatabaseObject()->prepare("
            CREATE TABLE IF NOT EXISTS User(
                id INTEGER PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(500) NOT NULL,
                email VARCHAR(500) NOT NULL,
                login VARCHAR(500) NOT NULL,
                password VARCHAR(500) NOT NULL
            );
        ");
        $query->execute();
    }

    static function find(Filter $filter, bool $fetchAll = true): User | array {
        return ActiveRecord::find("User",
            "User::sync",
            "User::setRows",
            $filter,
            $fetchAll
        );
    }
}