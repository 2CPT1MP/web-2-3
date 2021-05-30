<?php require_once('person.validator.php');

class ExamineeValidator extends PersonValidator {
    private array $formData;

    public function __construct($formData) {
        parent::__construct($formData);
        $this->formData = $formData;
        $this->validators["sender-age"] = isset($formData["sender-age"]) && $this->isValidAge($formData["sender-age"]);
        $this->errors["sender-age"] = "Неверный возраст";
        $this->validators["sender-group"] = isset($formData["sender-group"]) && $this->isValidGroupName($formData["sender-group"]);
        $this->errors["sender-group"] = "Неверный номер группы";
    }

    public function validate(): array {
        $msg = "";
        $valid = true;

        foreach ($this->validators as $field => $validator) {
            if (!$validator) {
                $valid = false;
                $msg .= "{$this->errors[$field]}<br>";
            }
        }
        return [$valid, "$msg"];
    }

    public function isValidGroupName($groupName): bool {
        $validGroupNamePattern = '/^[А-Я]+\/[бма]-\d\d-\d-[оз]+$/u';
        return preg_match($validGroupNamePattern, $groupName);
    }

    public function isValidAge($age): bool {
        return is_numeric($age) && $age > 16 && $age < 150;
    }

    private function getFormAnswersFor(string $question): array {
        return $this->formData[str_replace(' ', '_', $question)];
    }

    public function verifyResults($formData): Result {
        $resultName =
        $test = Test::findById($formData["test-id"]);
        $questions = $test->getTestQuestions();
        $testResult = new Result("Результат");
        $testResult->setTimestamp(date('Y-m-d H:i:s'));
        $testResult->setStudentName($formData["sender-name"]);

        foreach ($questions as $question) {
            $formAnswersArray = $this->getFormAnswersFor($question->getQuestion());
            $rightAnswers =  $question->getRightAnswers();

            foreach ($rightAnswers as $rightAnswer) {
                if (in_array($rightAnswer->getText(), $formAnswersArray))
                    $answer = new Answer($rightAnswer->getText(), "RIGHT");
                else
                    $answer = new Answer($rightAnswer->getText(), "WRONG");
                $answer->setTestQuestionId($question->getId());
                $testResult->addAnswer($answer);
            }
        }
        return $testResult;
    }
}