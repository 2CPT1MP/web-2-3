<?php require_once(__DIR__ . '/../../views/register.view.php');
require_once(__DIR__ . '/../../models/user.model.php');

class RegisterController extends RestController {

    public function GET(Request $request): string {
        return RegisterView::render('/');
    }

    public function validateCredentials($requestBody): bool {
        $login = trim($requestBody['login']);
        $email = trim($requestBody['email']);

        $loginFilter = new Filter();
        $loginFilter->addCondition('login', $login);

        $emailFilter = new Filter();
        $emailFilter->addCondition('email', $email);

        return count(User::find($loginFilter)) === 0 && count(User::find($emailFilter)) === 0;
    }

    public function POST(Request $request): string {
        session_start();
        $requestBody = $request->getBody();
        $notPresent = $this->validateCredentials($requestBody);

        if ($notPresent) {
            $user = new User();
            $user->setName($requestBody['name']);
            $user->setEmail($requestBody['email']);
            $user->setLogin($requestBody['login']);
            $user->setPassword($requestBody['password']);
            $user->save();

            header("Location: {$requestBody['redirectedFrom']}");
        }
        return MessageView::render("Ошибка", "Пользователь существует");
    }
}