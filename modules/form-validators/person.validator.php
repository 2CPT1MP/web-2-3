<?php

class PersonValidator {
    protected array $errors = [];
    protected array $validators = [];

    public function __construct($formData) {
        $this->validators["sender-name"] = isset($formData["sender-name"]) && $this->isValidName($formData["sender-name"]);
        $this->errors["sender-name"] = "Неверное имя";

        $this->validators["sender-gender"] = isset($formData["sender-gender"]) && $this->isValidGender($formData["sender-gender"]);
        $this->errors["sender-gender"] = "Неверный пол";
    }

    public static function isValidName(string $name): bool {
        $validNamePattern = '/^[А-ЯA-Z]([а-яa-z])+\s[А-ЯA-Z]([а-яa-z])+\s[А-ЯA-Z]([а-яa-z])+$/u';
        return preg_match($validNamePattern, $name);
    }

    public static function isValidGender(string $gender): bool {
        return $gender === 'Мужской' || $gender === 'Женский';
    }
}