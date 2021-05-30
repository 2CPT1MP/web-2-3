<?php require_once(__DIR__ . '/../../modules/form-validators/examinee.validator.php');
require_once(__DIR__ . '/../../views/test/test-results.view.php');
require_once(__DIR__ . '/../../views/test/test-results-list.view.php');

class TestResultController extends RestController {

    public function showAllResults(): string {
        $testResults = Result::findAll();
        return TestResultsListView::render($testResults);
    }

    public function GET(Request $request): string {
        [$authenticated, $role] = MessagesController::checkAuthorization();

        if (!$authenticated)
            header("Location: /login");

        if (!isset($request->getParams()["id"]))
            return $this->showAllResults();

        $testId = $request->getParams()["id"];
        $properResult = Result::findById($testId);

        if ($properResult)
            return TestResultsView::render($properResult);
        return MessageView::render("Результат не найден", "Результат теста #$testId не найден");
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}