<?php require_once('../views/history.view.php');

class HistoryController extends RestController {

    public function GET(Request $request): string {
        return HistoryView::render();
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}