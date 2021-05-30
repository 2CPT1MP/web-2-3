<?php

abstract class Order {
    protected string $fieldName;
    protected string $orderDirection;

    public function __construct(string $fieldName, string $orderDirection) {
        $this->fieldName = $fieldName;
        $this->orderDirection = $orderDirection;
    }

    public function toSql(): string {
        return "ORDER BY $this->fieldName $this->orderDirection";
    }
}

class AscendingOrder extends Order {
    public function __construct(string $fieldName) {
        parent::__construct($fieldName, "ASC");
    }
}

class DescendingOrder extends Order {
    public function __construct(string $fieldName) {
        parent::__construct($fieldName, "DESC");
    }
}