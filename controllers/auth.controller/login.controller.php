<?php

class LoginController extends RestController {

    public function GET(Request $request): string {
        $student = new Student();
        return BioView::render($student->getBio());
    }

    public function validateCredentials($requestBody): bool {
        $username = trim($requestBody['username']);
        $password = trim($requestBody['password']);

        [$adminUsername, $adminPassword] = file(__DIR__ . '/../../files/pswd.inc');
        return $username === trim($adminUsername) && $password === trim($adminPassword);
    }

    public function POST(Request $request): string {
        session_start();
        $requestBody = $request->getBody();

        $authorized = $this->validateCredentials($requestBody);

        if ($authorized) {
            $_SESSION['username'] = $requestBody['username'];
            $_SESSION['password'] = $requestBody['password'];

            header("Location: {$requestBody['redirectedFrom']}");
        }
        return MessageView::render("Ошибка", "Неверные данные");
    }
}