<?php
require_once(__DIR__ . "/../../core/routing/controller.core.php");

class BlogImageController extends RestController {

    private function sendImage(string $id): bool {
        header('Content-Type: image/png');
        return readfile(__DIR__ . "/../../files/userImages/$id.png");
    }

    public function GET(Request $request): string {
        if (isset($request->getParams()["id"]))
            return $this->sendImage($request->getParams()["id"]);
        return MessageView::render("Ошибка", "Нет такой картинки");
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неразрешенная операция");
    }
}