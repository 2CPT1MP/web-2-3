<?php require_once('../views/interests.view.php');

class InterestsController extends RestController {
    public function showInterests(Interests $interests): string {
        return InterestsView::render($interests);
    }

    public function GET(Request $request): string {
        $student = new Student();
        return $this->showInterests($student->getInterests());
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}