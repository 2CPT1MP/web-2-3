<?php
require_once(__DIR__ . '/../core/active-record/entity.core.php');
require_once(__DIR__ . '/../core/active-record/active-record.core.php');
require_once(__DIR__ . '/../core/active-record/filter.core.php');
require_once(__DIR__ . '/../core/active-record/order.core.php');

class BlogMessage implements IEntity {
    private int | null $id = null;
    private string | null $imagePath = null;
    private string $topic, $text, $timestamp;

    private function setId($id) { $this->id = $id; }
    public function setTopic(string $topic): void { $this->topic = $topic; }
    public function setImagePath(string $imagePath): void { $this->imagePath = $imagePath; }
    public function setText(string $text): void { $this->text = $text; }
    public function setTimestamp(string $timestamp): void { $this->timestamp = $timestamp; }

    public function getId(): ?int { return $this->id; }
    public function getTopic(): string { return $this->topic; }
    public function getImagePath(): string | null { return $this->imagePath; }
    public function getText(): string { return $this->text; }
    public function getTimestamp(): string { return $this->timestamp; }

    private function createNew(): bool {
        if (!isset($this->timestamp))
            $this->timestamp = date('Y-m-d H:i:s');

        $query = ActiveRecord::getDatabaseObject()->prepare("
                INSERT INTO BlogMessage(topic, text, imagePath, timestamp) 
                VALUES(:topic, :text, :imagePath, :timestamp);
        ");

        $this->bindValuesToQuery($query, false);
        $res = $query->execute();

        $this->setId(ActiveRecord::getDatabaseObject()->lastInsertId());
        return $res;
    }

    public function hasImage(): bool { return $this->imagePath !== null; }

    private function updateExisting(): bool {
        if (!isset($this->timestamp))
            $this->timestamp = date('Y-m-d H:i:s');
        $query = ActiveRecord::getDatabaseObject()->prepare("
            UPDATE BlogMessage 
            SET topic = :topic,
                text = :text, 
                imagePath = :imagePath, 
                timestamp = :timestamp
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
            DELETE FROM BlogMessage
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

    /** @return BlogMessage[] */
    static function findAll(): array {
        self::sync();
        return self::find(new Filter());
    }

    static function deleteAll(): bool {
        $success = true;
        foreach(BlogMessage::findAll() as $message) {
            $result = $message->delete();
            if (!$result)
                $success = false;
        }
        return $success;
    }

    static function findAllForPage(int $page, int $recordsPerPage): array {
        self::sync();
        $filter = new Filter();
        $filter->setLimit(new Limit($page, $recordsPerPage));
        return self::find($filter);
    }

    public static function getPageCount(int $recordsPerPage): int {
        self::sync();
        $query = "SELECT COUNT(*) FROM BlogMessage;";
        $statement = ActiveRecord::getDatabaseObject()->query($query);
        if (!$statement)
            return 0;
        $recordCount = $statement->fetch(PDO::FETCH_NUM)[0];

        return ceil($recordCount / $recordsPerPage);
    }

    public static function getCount(): int {
        self::sync();
        $query = "SELECT COUNT(*) FROM BlogMessage;";
        $statement = ActiveRecord::getDatabaseObject()->query($query);
        if (!$statement)
            return 0;
        return $statement->fetch(PDO::FETCH_NUM)[0];
    }

    public static function setRows($row): BlogMessage {
        $newObject = new BlogMessage();
        $newObject->setId($row["id"]);
        $newObject->setTopic($row["topic"]);
        $newObject->setText($row["text"]);
        if (isset($row["imagePath"]))
            $newObject->setImagePath($row["imagePath"]);
        $newObject->setTimestamp($row["timestamp"]);
        return $newObject;
    }

    private function bindValuesToQuery($query, bool $includeId = true): void {
        if ($includeId)
            $query->bindParam(":id", $this->id);
        $query->bindParam(":topic", $this->topic);
        $query->bindParam(":text", $this->text);
        $query->bindParam(":imagePath", $this->imagePath);
        $query->bindParam(":timestamp", $this->timestamp);
    }

    public static function sync() {
        $query = ActiveRecord::getDatabaseObject()->prepare("
            CREATE TABLE IF NOT EXISTS BlogMessage(
                id INTEGER PRIMARY KEY AUTO_INCREMENT,
                topic VARCHAR(100) NOT NULL,
                text VARCHAR(500) NOT NULL,
                imagePath VARCHAR(100),
                timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            );
        ");
        $query->execute();
    }

    static function find(Filter $filter, bool $fetchAll = true): BlogMessage | array {
        return ActiveRecord::find("BlogMessage",
            "BlogMessage::sync",
            "BlogMessage::setRows",
            $filter,
            $fetchAll,
            new DescendingOrder("timestamp")
        );
    }
}