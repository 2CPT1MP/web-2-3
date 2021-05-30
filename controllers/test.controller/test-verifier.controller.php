<?php require_once(__DIR__ . '/../../modules/form-validators/examinee.validator.php');
require_once(__DIR__ . '/../../views/test/test-results.view.php');

class TestVerifierController extends RestController {

    public function POST(Request $request): string {
        $testData = $request->getBody();
        $validator = new ExamineeValidator($testData);
        [$validTest, $validationMsg] = $validator->validate();

        if (!$validTest)
            return MessageView::render('Тест не пройден', $validationMsg);

        $result = $validator->verifyResults($testData);
        $result->save();

        return MessageView::render("Проверка теста",
            "<a href='/test/result?id={$result->getId()}'>Просмотреть результат теста</a>" . $validationMsg);
    }

    public function GET(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}