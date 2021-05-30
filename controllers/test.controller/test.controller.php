<?php require_once(__DIR__ . '/../../views/test/test.view.php');

class TestController extends RestController {

    public function GET(Request $request): string {
        $test = Test::findById(1);
        return TestView::render($test);
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}