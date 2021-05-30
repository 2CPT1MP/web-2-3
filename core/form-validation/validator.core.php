<?php

class ValidationResult {
    private bool $isValid;
    private string $errorMessage;

    public function isValid(): bool { return $this->isValid; }
    public function getErrorMessage(): string { return $this->errorMessage; }

    public function __construct(bool $isValid, string $errorMessage) {
        $this->isValid = $isValid;
        $this->errorMessage = $errorMessage;
    }
}

class ValidationRule {
    private $validateFunction;
    private string $fieldName, $errorMessage;

    public function getFieldName(): string { return $this->fieldName; }
    public function getValidateFunction() { return $this->validateFunction; }
    public function getErrorMessage(): string { return $this->errorMessage; }

    public function __construct(string $fieldName,
                                $validateFunction,
                                string $errorMessage) {
        $this->fieldName = $fieldName;
        $this->validateFunction = $validateFunction;
        $this->errorMessage = $errorMessage;
    }
}

class FormValidator {
    /** @var ValidationRule[] $rules */
    private array $rules;

    public function addRule(string $fieldName,
                            $validateFunction,
                            string $errorMessage
    ): void {
        $this->rules[] = new ValidationRule($fieldName, $validateFunction, $errorMessage);
    }

    public function validate($formData): ValidationResult {
        $errorMessage = "";
        $isValid = true;

        foreach ($this->rules as $rule) {
            $formField = $rule->getFieldName();
            $fieldValidator = $rule->getValidateFunction();

            if (!isset($formData[$formField]) || !$fieldValidator($formData[$formField])) {
                $isValid = false;
                $errorMessage .= "{$rule->getErrorMessage()}<br>";
            }
        }
        return new ValidationResult($isValid, $errorMessage);
    }
}