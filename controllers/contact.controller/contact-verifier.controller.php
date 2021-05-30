<?php
require_once(__DIR__ . '/../../modules/form-validators/contact.validator.php');
require_once(__DIR__ . '/../../views/guest-book/message.view.php');
require_once(__DIR__ . '/../../models/guest-book-item.model.php');

class ContactVerifierController extends RestController {

    private function saveMessage($body): bool {
        $data = new GuestBookItem();
        $data->setName($body["sender-name"]);
        $data->setGender($body["sender-gender"]);

        $data->setDayOfBirth(intval($body["sender-day"]));
        $data->setMonthOfBirth(intval($body["sender-month"]));
        $data->setYearOfBirth(intval($body["sender-year"]));

        $data->setEmail($body["sender-email"]);
        $data->setPhone($body["sender-phone"]);
        $data->setMessage($body["sender-msg"]);

        return $data->save();
    }

    public function POST(Request $request): string {
        $body = $request->getBody();
        $validator = new ContactValidator($body);
        [$resultIsValid, $errorMessage] = $validator->validate();

        if ($resultIsValid) {
            $savedSuccessfully = $this->saveMessage($body);
            if (!$savedSuccessfully)
                return MessageView::render("Ошибка", "Произошла ошибка при отправке сообщения");
            return MessageView::render("Успешная проверка", "Введенная информация сохранена");
        }
        return MessageView::render('Проверка не прошла', $errorMessage);
    }

    public function GET(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}