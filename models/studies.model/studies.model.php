<?php

class Studies {
    private string $university, $department, $studyProgram;
    private array $schedule = [];

    public function getUniversity(): string { return $this->university; }
    public function setUniversity(string $university): void { $this->university = $university; }

    public function getDepartment(): string { return $this->department; }
    public function setDepartment(string $department): void { $this->department = $department; }

    public function getStudyProgram(): string { return $this->studyProgram; }
    public function setStudyProgram(string $studyProgram): void { $this->studyProgram = $studyProgram; }

    public function getSchedule(): array { return $this->schedule; }

    public function addScheduleRecord(ScheduleRecord $scheduleRecord): void {
        $this->schedule[] = $scheduleRecord;
    }
}