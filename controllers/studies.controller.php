<?php require_once('../views/studies.view.php');

class StudiesController extends RestController {
    public function showStudies(Studies $studies): string {
        return StudiesView::render($studies);
    }

    public function GET(Request $request): string {
        $student = new Student();
        return $this->showStudies($student->getStudies());
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}