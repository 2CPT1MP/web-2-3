<?php
require_once('../views/index.view.php');
require_once('../models/student.model.php');

class IndexController extends RestController {

    public function GET(Request $request): string {
        $student = new Student();
        return IndexView::render($student);
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}