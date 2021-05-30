<?php require_once(__DIR__ . '/../header.view.php');
require_once(__DIR__ . "/../../models/test.model/test-question.model.php");

class TestResultsListView {
    /** @param Result[] $results */
    public static function render(array $results): string {
        $html = HeaderView::render("Результаты тестирования");
        $html .= "<section class='card'><h2>Список результатов тестов</h2><table>";

        foreach ($results as $result) {
            $html .= "<tr><td>#{$result->getId()}</td><td>{$result->getTimestamp()}</td><td>{$result->getStudentName()}</td>";
            $html .= "<td><b><a href='/test/result?id={$result->getId()}'>Просмотреть</a></b></td><tr>";
        }

        return "</table></section>" . $html;
    }
}