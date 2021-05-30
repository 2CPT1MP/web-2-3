<?php require_once('../views/admin.view.php');

class AdminController extends RestController {

    public function GET(Request $request): string {
        [$authenticated, $role] = MessagesController::checkAuthorization();

        if (!$authenticated || $role !== "ADMIN")
            header("Location: /login");
        return AdminView::render();
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}