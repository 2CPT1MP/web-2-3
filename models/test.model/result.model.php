<?php require_once('answer.model.php');
require_once(__DIR__ . "/../../core/active-record/active-record.core.php");

class Result implements IEntity {
    private array $answers = [];
    private string | null $title;
    private int | null $id = null;
    private string $studentName, $timestamp;

    public function getStudentName(): string { return $this->studentName; }
    public function setStudentName(string $studentName): void { $this->studentName = $studentName;}

    public function getTimestamp(): string { return $this->timestamp;}
    public function setTimestamp(string $timestamp): void { $this->timestamp = $timestamp; }

    public function getTitle(): string | null { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }

    public function setAnswers(array $testQuestions): void {
        foreach ($testQuestions as $question) {
            $question->setResultId($this->id);
        }
        $this->answers = $testQuestions;
    }
    public function addAnswer(Answer $answer): void {
        $answer->setResultId($this->id);
        $this->answers[] = $answer;
    }
    public function getAnswers(): array { return $this->answers; }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }
    public function __construct(string $title) { $this->title = $title; }

    private function createNew(): bool {
        $query = ActiveRecord::getDatabaseObject()->prepare("
            INSERT INTO Result(title, studentName, timestamp) 
            VALUES(:title, :studentName, :timestamp);
        ");

        $this->bindValuesToQuery($query, false);
        $res = $query->execute();

        $this->setId(ActiveRecord::getDatabaseObject()->lastInsertId());
        $this->saveAnswers();
        return $res;
    }

    private function updateExisting(): bool {
        $query = ActiveRecord::getDatabaseObject()->prepare("
             UPDATE Result 
             SET title = :title,
                 studentName = :studentName,
                 timestamp = :timestamp
             WHERE id = :id;
        ");

        $this->bindValuesToQuery($query);
        $result = $query->execute();
        $this->saveAnswers();
        return $result;
    }

    public function save(): bool {
        self::sync();
        return ($this->id)? $this->updateExisting() : $this->createNew() ;
    }

    private function saveAnswers() {
        foreach ($this->answers as $answer) {
            $answer->setResultId($this->id);
            $answer->save();
        }
    }

    public function delete(): bool {
        self::sync();
        if ($this->id === null)
            return false;

        $query = ActiveRecord::getDatabaseObject()->prepare("
            DELETE FROM Result
            WHERE id = :id;
        ");
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    public static function findById(int $id): Result | null {
        self::sync();
        $idFilter = new Filter();
        $idFilter->addCondition("id", $id);
        return self::find($idFilter, false);
    }

    public static function findAll(): array {
        self::sync();
        return self::find(new Filter());
    }

    public static function sync() {
        $query = ActiveRecord::getDatabaseObject()->prepare("
            CREATE TABLE IF NOT EXISTS Result(
                id INTEGER PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(250) NOT NULL,
                studentName VARCHAR(250) NOT NULL,
                timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);
            ");
        $query->execute();
    }

    static function setRows($row): Result {
        $newObject = new Result($row["title"]);
        $newObject->setId($row["id"]);
        $newObject->setStudentName($row["studentName"]);
        $newObject->setTimestamp($row["timestamp"]);
        $newObject->setAnswers(Answer::findAllByResultId($newObject->id));
        return $newObject;
    }

    private function bindValuesToQuery($query, bool $includeId = true): void {
        if ($includeId)
            $query->bindParam(":id", $this->id);
        $query->bindParam(":title", $this->title);
        $query->bindParam(":studentName", $this->studentName);
        $query->bindParam(":timestamp", $this->timestamp);
    }

    static function find(Filter $filter, bool $fetchAll = true): Result | array {
        return ActiveRecord::find("Result",
            "Result::sync",
            "Result::setRows",
            $filter,
            $fetchAll
        );
    }
}