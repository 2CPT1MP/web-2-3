<?php
require_once(__DIR__ . "/../../core/active-record/active-record.core.php");
require_once(__DIR__ . "/../../models/test.model/result.model.php");
require_once(__DIR__ . "/../../core/active-record/entity.core.php");

class Answer implements IEntity {
    private string $type = "RIGHT";
    private string $text;
    private int | null $id = null, $testQuestionId = null, $resultId = null;


    public function getResultId(): ?int { return $this->resultId; }
    public function setResultId(?int $resultId): void { $this->resultId = $resultId; }

    public function setTestQuestionId(int | null $testQuestionId): void { $this->testQuestionId = $testQuestionId; }
    public function getTestQuestionId(): ?int { return $this->testQuestionId; }

    public function getType(): string { return $this->type; }
    public function setType(string $type): void { $this->type = $type; }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getText(): string { return $this->text; }
    public function setText(string $text): void { $this->text = $text; }


    public function __construct(string $text = "", string $type = "RIGHT") {
        $this->text = $text;
        $this->type = $type;
    }

    private function createNew(): bool {
        $query = ActiveRecord::getDatabaseObject()->prepare("
            INSERT INTO Answer(type, text, testQuestionId, resultId) 
            VALUES(:type, :text, :testQuestionId, :resultId);
        ");

        $this->bindValuesToQuery($query, false);
        $res = $query->execute();

        $this->setId(ActiveRecord::getDatabaseObject()->lastInsertId());
        return $res;
    }

    private function updateExisting(): bool {
        $query = ActiveRecord::getDatabaseObject()->prepare("
            UPDATE Answer 
            SET type = :type, text = :text, testQuestionId = :testQuestionId, resultId = :resultId
            WHERE id = :id;
        ");

        $this->bindValuesToQuery($query);
        return $query->execute();
    }

    public function save(): bool {
        self::sync();
        return ($this->id)? $this->updateExisting() : $this->createNew();
    }

    public function delete(): bool {
        self::sync();
        if ($this->id === null) return false;

        $query = ActiveRecord::getDatabaseObject()->prepare("
            DELETE FROM Answer
            WHERE id = :id;
        ");
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    public static function findById(int $id): Answer | null {
        $filter = new Filter();
        $filter->addCondition("id", $id);
        return self::find($filter);
    }

    public static function findAllByQuestionId(int $questionId, bool $includeResults = true): array {
        $filter = new Filter();
        $filter->addCondition("testQuestionId", $questionId);

        if (!$includeResults)
            $filter->addCondition("resultId", null);
        return self::find($filter);
    }

    public static function findAllByResultId(int $resultId): array {
        $filter = new Filter();
        $filter->addCondition("resultId", $resultId);
        return self::find($filter);
    }

    public static function findAll(): array {
        self::sync();
        return self::find(new Filter());
    }

    public static function sync() {
        TestQuestion::sync();
        Result::sync();
        $query = ActiveRecord::getDatabaseObject()->prepare("
            CREATE TABLE IF NOT EXISTS Answer(
                id INTEGER PRIMARY KEY AUTO_INCREMENT,
                type VARCHAR(15) NOT NULL CHECK (type IN ('RIGHT', 'WRONG')),
                text VARCHAR(50),
                testQuestionId INTEGER NOT NULL,
                resultId INTEGER,
                FOREIGN KEY (testQuestionId) REFERENCES TestQuestion(id) ON DELETE CASCADE,
                FOREIGN KEY (resultId) REFERENCES Result(id) ON DELETE CASCADE);
        ");
        $query->execute();
    }

    public static function setRows($row): Answer {
            $newObject = new Answer();
            $newObject->setId($row["id"]);
            $newObject->setType($row["type"]);
            $newObject->setText($row["text"]);
            $newObject->setTestQuestionId($row["testQuestionId"]);
            $newObject->setResultId($row["resultId"]);
            return $newObject;
    }

    private function bindValuesToQuery($query, bool $includeId = true): void {
        if ($includeId)
            $query->bindParam(":id", $this->id);

        $query->bindParam(":type", $this->type);
        $query->bindParam(":text", $this->text);
        $query->bindParam(":testQuestionId", $this->testQuestionId);
        $query->bindParam(":resultId", $this->resultId);
    }

    static function find(Filter $filter, bool $fetchAll = true): Answer | array {
        return ActiveRecord::find("Answer",
                            "Answer::sync",
                            "Answer::setRows",
                            $filter,
                            $fetchAll
        );
    }
}
