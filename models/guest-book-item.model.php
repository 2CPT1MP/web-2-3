<?php
require_once('../core/active-record/active-record.core.php');

class GuestBookItem extends ActiveRecord {
    private static string $FILE_NAME = '../files/messages.inc';
    private string $name, $gender, $email, $phone, $message;
    private int $dayOfBirth, $monthOfBirth, $yearOfBirth;
    private string $saveDate;

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getGender(): string {
        return $this->gender;
    }

    public function setGender(string $gender): void {
        $this->gender = $gender;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function setPhone(string $phone): void {
        $this->phone = $phone;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }

    public function getDayOfBirth(): int {
        return $this->dayOfBirth;
    }

    public function setDayOfBirth(int $dayOfBirth): void {
        $this->dayOfBirth = $dayOfBirth;
    }

    public function getMonthOfBirth(): int {
        return $this->monthOfBirth;
    }

    public function setMonthOfBirth(int $monthOfBirth): void {
        $this->monthOfBirth = $monthOfBirth;
    }

    public function getYearOfBirth(): int {
        return $this->yearOfBirth;
    }

    public function setYearOfBirth(int $yearOfBirth): void {
        $this->yearOfBirth = $yearOfBirth;
    }

    public function getSaveDate(): string {
        return $this->saveDate;
    }

    public function setSaveDate(string $saveDate): void {
        $this->saveDate = $saveDate;
    }

    public function save(): bool {
        $this->saveDate = date('d.m.Y H:i:s');
        $data = "$this->saveDate;$this->name;$this->gender;$this->email;$this->phone;$this->dayOfBirth;$this->monthOfBirth;$this->yearOfBirth;$this->message\n";
        $file = fopen(self::$FILE_NAME, 'a');

        if (!$file || !fwrite($file, $data))
            return false;
        return fclose($file);
    }

    public function delete(): bool {
        // TODO: Implement delete() method.
        return false;
    }

    public static function findById(int $id): GuestBookItem | null {
        // TODO: Implement findById() method.
        return null;
    }

    public static function findAll(): array {
        $models  = [];
        $lines = file(self::$FILE_NAME, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $fields = explode(';', $line);

            if (count($fields) !== 9)
                continue;

            $newModel = new GuestBookItem();
            $newModel->setSaveDate($fields[0]);
            $newModel->setName($fields[1]);
            $newModel->setGender($fields[2]);
            $newModel->setEmail($fields[3]);
            $newModel->setPhone($fields[4]);
            $newModel->setDayOfBirth(intval($fields[5]));
            $newModel->setMonthOfBirth(intval($fields[6]));
            $newModel->setYearOfBirth(intval($fields[7]));
            $newModel->setMessage($fields[8]);
            $models[] = $newModel;
        }
        function sortFunction(GuestBookItem $a, GuestBookItem $b ): false | int {
            return strtotime($b->getSaveDate()) - strtotime($a->getSaveDate());
        }
        usort($models, "sortFunction");
        return $models;
    }
}