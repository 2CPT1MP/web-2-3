<?php

class LogoutController extends RestController {

    public function GET(Request $request): string {
        if (!isset($_SESSION))
            session_start();
        session_destroy();
        unset($_SESSION);

        header("Location: /");
        return "";
    }


    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}