<?php require_once('../views/bio.view.php');

class BioController extends RestController {

    public function GET(Request $request): string {
        $student = new Student();
        return BioView::render($student->getBio());
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}