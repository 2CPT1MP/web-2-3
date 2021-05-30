<?php
require_once(__DIR__ . "/../../core/active-record/active-record.core.php");
require_once(__DIR__ . "/../../core/active-record/entity.core.php");
require_once("test.model.php");

class TestQuestion implements IEntity {
    private int | null $id = null;
    private int | null $testId = null;
    private string $question;
    private string $type;
    private array $rightAnswers = [];
    private array $wrongAnswers = [];

    public function getRightAnswers(): array { return $this->rightAnswers; }
    public function getWrongAnswers(): array { return $this->wrongAnswers; }

    public function addRightAnswer(Answer $answer): void { $this->rightAnswers[] = $answer; }
    public function addWrongAnswer(Answer $answer) { $this->wrongAnswers[] = $answer; }

    public function getQuestion(): string { return $this->question; }
    public function setQuestion(string $question): string { return $this->question = $question; }

    public function getType(): string { return $this->type; }
    public function setType(string $type): string { return $this->type = $type; }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getTestId(): ?int { return $this->testId; }
    public function setTestId(?int $testId): void { $this->testId = $testId; }


    public function __construct(string $question, string $type='SINGLE_SELECT') {
        $this->type = $type;
        $this->question = $question;
    }

    private function saveAnswers() {
        foreach ($this->wrongAnswers as $wrongAnswer) {
            $wrongAnswer->setTestQuestionId($this->id);
            $wrongAnswer->save();
        }
        foreach ($this->rightAnswers as $rightAnswer) {
            $rightAnswer->setTestQuestionId($this->id);
            $rightAnswer->save();
        }
    }

    public function createNew(): bool {
        $query = ActiveRecord::getDatabaseObject()->prepare("
            INSERT INTO TestQuestion(type, question, testId) 
            VALUES(:type, :question, :testId);
        ");

        $this->bindValuesToQuery($query, false);
        $res = $query->execute();
        $this->setId(ActiveRecord::getDatabaseObject()->lastInsertId());
        $this->saveAnswers();
        return $res;
    }

    public function updateExisting(): bool {
        $query = ActiveRecord::getDatabaseObject()->prepare("
            UPDATE TestQuestion 
            SET type = :type, question = :question, testId = :testId
            WHERE id = :id;
        ");

        $this->bindValuesToQuery($query);
        $this->saveAnswers();
        return $query->execute();
    }

    public function save(): bool {
        $this->sync();
        return ($this->id)? $this->updateExisting() : $this->createNew();
    }

    public static function findAll(): array {
        self::sync();
        return self::find(new Filter());
    }

    public function delete(): bool {
        self::sync();
        if ($this->id === null) return false;

        $query = ActiveRecord::getDatabaseObject()->prepare("
            DELETE FROM TestQuestion
            WHERE id = :id;
        ");
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    public static function findById(int $id): TestQuestion | null {
        $idFilter = new Filter();
        $idFilter->addCondition("id", $id);
        return self::find($idFilter, false);
    }

    public static function findAllByTestId(int $testId): array {
        $testIdFilter = new Filter();
        $testIdFilter->addCondition("testId", $testId);

        return self::find($testIdFilter);
    }

    public function findAllAnswers(bool $includeResults = true): void {
        if ($this->id === null) return;
        $answers = Answer::findAllByQuestionId($this->id, $includeResults);

        foreach ($answers as $answer) {
            switch ($answer->getType()) {
                case "RIGHT":
                    $this->rightAnswers[] = $answer;
                    break;
                case "WRONG":
                    $this->wrongAnswers[] = $answer;
                    break;
            }
        }
    }

    public static function setRows($row): TestQuestion {
        $newObject = new TestQuestion($row["question"]);
        $newObject->setId($row["id"]);
        $newObject->setTestId($row["testId"]);
        $newObject->setType($row["type"]);
        $newObject->findAllAnswers(false);
        return $newObject;
    }

    private function bindValuesToQuery($query, bool $includeId = true): void {
        if ($includeId)
            $query->bindParam(":id", $this->id);
        $query->bindParam(':type', $this->type);
        $query->bindParam(':question', $this->question);
        $query->bindParam(':testId', $this->testId);
    }

    public static function sync() {
        Test::sync();
        $query = ActiveRecord::getDatabaseObject()->prepare("
            CREATE TABLE IF NOT EXISTS TestQuestion(
                id INTEGER PRIMARY KEY AUTO_INCREMENT,
                type VARCHAR(50) NOT NULL CHECK 
                    (type IN ('SINGLE_SELECT', 'MULTIPLE_SELECT', 'TEXT', 'RADIO')),
                question VARCHAR(1000),
                testId INTEGER,
                FOREIGN KEY (testId) REFERENCES Test(id) ON DELETE CASCADE);
        ");
        $query->execute();
    }

    static function find(Filter $filter, bool $fetchAll = true): TestQuestion | array {
        return ActiveRecord::find("TestQuestion",
            "TestQuestion::sync",
            "TestQuestion::setRows",
            $filter,
            $fetchAll
        );
    }
}