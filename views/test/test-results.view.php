<?php require_once(__DIR__ . '/../header.view.php');
require_once(__DIR__ . "/../../models/test.model/test-question.model.php");

class TestResultsView {
    public static function render(Result $result): string {
        $html = HeaderView::render("Результаты тестирования");
        $html .= "<section class='card'><h2>{$result->getTitle()}</h2>";
        $html .= "<table><tr><td class='th'>Отправлен</td><td>{$result->getTimestamp()}</td>";
        $html .= "<tr><td class='th'>Тестируемый</td><td>{$result->getStudentName()}</td></tr></table>";
        $questions = [];
        $number = 1;

        $maxScore = count($result->getAnswers());
        $actualScore = 0;

        foreach ($result->getAnswers() as $answer)
            $questions[$answer->getTestQuestionId()][] = $answer;

        foreach ($questions as $question) {
            $testQuestionId = $question[0]->getTestQuestionId();
            $testQuestion = TestQuestion::findById($testQuestionId);
            $html .= "<h3>Вопрос $number: {$testQuestion->getQuestion()}</h3>";

            foreach ($question as $answer) {
                if ($answer->getType() === "RIGHT") {
                    $html .= "<input type='checkbox' checked disabled><span class='correct'> {$answer->getText()} (верный)</span><br>";
                    $actualScore++;
                }
                else
                    $html .= "<input type='checkbox' disabled><span class='wrong'> {$answer->getText()} (не указан / неверен)</span><br>";
            }
            $number++;
        }
        $percent = round($actualScore / $maxScore * 100);
        $html .= "<h2>Итог: $actualScore/ $maxScore ($percent%)</h2>";
        return "</section>" . $html;
    }
}