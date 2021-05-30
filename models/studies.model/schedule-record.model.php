<?php

class ScheduleRecord {
    private int $number;
    private string $title;
    private array $semesters = [];
    private static int $idCounter = 1, $semesterCount = 0, $fieldCount = 0;

    public static function getSemestersCount(): int { return self::$semesterCount; }
    public static function setSemestersCount(int $semesterCount): void { self::$semesterCount = $semesterCount; }

    public function getNumber(): int { return $this->number; }
    public function getTitle(): string { return $this->title; }
    public function getSemesters(): array { return $this->semesters; }

    public static function getFieldCount(): int { return self::$fieldCount; }
    public static function setFieldCount(int $fieldCount): void { self::$fieldCount = $fieldCount; }

    public function __construct(string $title) {
        $this->title = $title;
        $this->number = self::$idCounter++;

        for ($semester = 0; $semester < self::$semesterCount; $semester++)
            for ($field = 0; $field < self::$fieldCount; $field++)
                $this->semesters[$semester][$field] = "";
    }

    public function addFieldToSemester(int $semesterNumber, int $fieldNumber, int $hoursData): void {
       $this->semesters[$semesterNumber-1][$fieldNumber-1] = $hoursData;
    }
}