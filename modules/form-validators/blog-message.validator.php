<?php
require_once(__DIR__ . "/../../core/form-validation/validator.core.php");

class BlogMessageValidator extends FormValidator {

    public static function isValidTopic(string $topic): bool {
        $topicPattern = '/^((\w|\d)+\s*)+$/u';
        return preg_match($topicPattern, $topic);
    }

    public static function isValidText(string $text): bool {
        $textPattern = '/^([^<>]){10,500}$/u';
        return preg_match($textPattern, $text);
    }

    public function __construct() {
        $this->addRule(
            "topic",
            [BlogMessageValidator::class, 'isValidTopic'],
            "Некорректная тема сообщения"
        );

        $this->addRule(
            "text",
            [BlogMessageValidator::class, 'isValidText'],
            "Некорректное содержимое сообщения"
        );
    }
}