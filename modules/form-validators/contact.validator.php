<?php require_once('person.validator.php');

class ContactValidator extends PersonValidator {
    public function __construct($formData) {
        parent::__construct($formData);

        $this->validators["sender-date"] = isset($formData["sender-day"]) && isset($formData["sender-month"]) && isset($formData["sender-year"]) &&
                                           $this->isValidDate($formData["sender-day"], $formData["sender-month"] + 1, $formData["sender-year"]);
        $this->errors["sender-date"] = "Неверная дата рождения";

        $this->validators["sender-email"] = isset($formData["sender-email"]) && $this->isValidEmail($formData["sender-email"]);
        $this->errors["sender-email"] = "Неверный email";

        $this->validators["sender-phone"] = isset($formData["sender-phone"]) && $this->isValidPhoneNumber($formData["sender-phone"]);
        $this->errors["sender-phone"] = "Неверный телефон";
    }

    public function validate(): array {
        $msg = "";
        $valid = true;

        foreach ($this->validators as $field => $validator) {
            if (!$validator) {
                $valid = false;
                $msg .= "{$this->errors[$field]}<br/>";
            }
        }
        return [$valid, $msg];
    }

    public static function isValidEmail(string $email): bool {
        $validEmailPattern = '/^[A-Za-z0-9_]+[@][A-Za-z0-9_]+[.][A-Za-z0-9_]+$/';
        return preg_match($validEmailPattern, $email);
    }

    public static function isValidPhoneNumber(string $phoneNumber): bool {
        $validPhoneNumberPattern = '/^[+][7|3][0-9]{9,11}$/';
        return preg_match($validPhoneNumberPattern, $phoneNumber);
    }

    public static function isValidDate($day, $month, $year): bool {
        if (!is_numeric($day) || !is_numeric($month) || !is_numeric($year))
            return false;
        return checkdate($month, $day, $year);
    }
}