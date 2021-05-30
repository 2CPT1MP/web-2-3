<?php require_once('test-question.model.php');
require_once(__DIR__ . "/../../core/active-record/active-record.core.php");

class Test extends ActiveRecord {
    private array $testQuestions = [];
    private string | null $title;
    private int | null $id = null;

    public function getTitle(): string | null { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }

    public function addTestQuestion(TestQuestion $testQuestion): void { $this->testQuestions[] = $testQuestion; }
    public function getTestQuestions(): array { return $this->testQuestions; }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function setTestQuestions(array $testQuestions): void { $this->testQuestions = $testQuestions; }

    public function __construct(string $title) {  $this->title = $title; }


    public function save(): bool {
        self::sync();

        if ($this->id === null) {
            $query = parent::$databaseObject->prepare("
                INSERT INTO Test(title) 
                VALUES(:title);
            ");
            $query->bindParam(':title', $this->title);

            $res = $query->execute();
            $this->setId(parent::$databaseObject->lastInsertId());
            $this->saveQuestions();
            return $res;
        }

        $query = parent::$databaseObject->prepare("
                    UPDATE Test 
                    SET title = :title
                    WHERE id = :id;
        ");

        $query->bindParam(':id', $this->id);
        $this->saveQuestions();
        return $query->execute();
    }

    private function saveQuestions() {
        foreach ($this->testQuestions as $question) {
            $question->setTestId($this->id);
            $question->save();
        }
    }

    public function delete(): bool {
        self::sync();
        if ($this->id === null)
            return false;

        $query = parent::$databaseObject->prepare("
                    DELETE FROM Test
                    WHERE id = :id;
        ");
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    public static function findById(int $id): Test | null {
        self::sync();
        $query = parent::$databaseObject->prepare("
                    SELECT * 
                    FROM Test
                    WHERE id = :id;
        ");
        $query->bindParam(':id', $id);
        $query->execute();
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);

        if (!$resultSet || count($resultSet) < 1)
            return null;

        $newObject = new Test($resultSet["title"]);
        $newObject->setId($resultSet["id"]);
        $newObject->setTestQuestions(TestQuestion::findAllByTestId($newObject->id));

        return $newObject;
    }

    public static function findAll(): array {
        self::sync();
        $query = parent::$databaseObject->prepare("
                    SELECT * 
                    FROM Test;
        ");
        $query->execute();
        $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultSet) < 1)
            return [];

        $objects = [];
        foreach ($resultSet as $row) {
            $newObject = new Test($row["title"]);
            $newObject->setId($row["id"]);

            $questions = TestQuestion::findAllByTestId($newObject->id);
            $newObject->setTestQuestions($questions);
            $objects[] = $newObject;
        }
        return $objects;
    }

    public static function sync() {
        $query = parent::$databaseObject->prepare("
                    CREATE TABLE IF NOT EXISTS Test(
                        id INTEGER PRIMARY KEY AUTO_INCREMENT,
                        title VARCHAR(250) NOT NULL);
                    ");
        $query->execute();
    }
}