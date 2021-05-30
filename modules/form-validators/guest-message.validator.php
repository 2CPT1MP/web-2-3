<?php

require_once(__DIR__ . "/../../core/form-validation/validator.core.php");

class GuestMessageValidator extends FormValidator {

    public static function isValidDate(string $date): bool {
        $datePattern = '/^\d{1,2}\.\d{1,2}\.\d{4}\s\d{2}:\d{2}:\d{2}$/u';
        return preg_match($datePattern, $date);
    }

    public function __construct() {
        $this->addRule(
            0,
            [GuestMessageValidator::class, 'isValidDate'],
            "Некорректная дата"
        );

        $this->addRule(
            1,
            [PersonValidator::class, 'isValidName'],
            "Некорректное имя"
        );

        $this->addRule(
            2,
            [PersonValidator::class, 'isValidGender'],
            "Некорректный пол"
        );

        $this->addRule(
            3,
            [ContactValidator::class, 'isValidEmail'],
            "Некорректный имейл"
        );

        $this->addRule(
            4,
            [ContactValidator::class, 'isValidPhoneNumber'],
            "Некорректный телефон"
        );
    }
}