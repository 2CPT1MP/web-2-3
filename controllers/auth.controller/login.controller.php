<?php

class LoginController extends RestController {

    public function GET(Request $request): string {
        return LoginView::render('/');
    }

    public function validateCredentials($requestBody): array {
        $username = trim($requestBody['username']);
        $password = trim($requestBody['password']);

        [$adminUsername, $adminPassword] = file(__DIR__ . '/../../files/pswd.inc');
        if ($username === trim($adminUsername) && $password === trim($adminPassword))
            return [true, 'ADMIN'];

        $userFilter = new Filter();
        $userFilter->addCondition('login', $username);
        $userFilter->addCondition('password', $password);

        $foundUser = User::find($userFilter);

        if (isset($foundUser[0]) ) {
            $_SESSION['name'] = $foundUser[0]->getName();
            return [true, "USER"];
        }
        return [false, "USER"];
    }

    public function POST(Request $request): string {
        session_start();
        session_destroy();
        unset($_SESSION);
        session_start();

        $requestBody = $request->getBody();
        [$authorized, $role] = $this->validateCredentials($requestBody);

        if ($authorized) {

            $_SESSION['username'] = $requestBody['username'];
            $_SESSION['password'] = $requestBody['password'];
            $_SESSION['role'] = $role;

            header("Location: {$requestBody['redirectedFrom']}");
        }
        return MessageView::render("Ошибка", "Неверные данные");
    }
}