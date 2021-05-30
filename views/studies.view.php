<?php
require_once('header.view.php');
require_once('../models/studies.model/studies.model.php');

class StudiesView {
    public static function render(Studies $studies): string {
        $html = HeaderView::render('Учеба');
        $html .= <<<STUDIES
                <section class="card">
                    <table>
                        <tr>
                            <td class="th">Университет</td>
                            <td>{$studies->getUniversity()}</td>
                        </tr>
                        <tr>
                            <td class="th">Кафедра</td>
                            <td>{$studies->getDepartment()}</td>
                        </tr>
                        <tr>
                            <td class="th">Направление подготовки</td>
                            <td>{$studies->getStudyProgram()}</td>
                        </tr>
                    </table>
                </section>
        STUDIES;

        $fieldCount = ScheduleRecord::getFieldCount();
        $semesterCount = ScheduleRecord::getSemestersCount();
        $colCount = $fieldCount * $semesterCount;

        $html .= "<table class='border-table'>
            <tr>
                <th rowspan='2'>№</th>
                <th rowspan='2'>Дисциплина</th>
                <th colspan={$colCount}>Часы</th>
            </tr>
        ";

        for ($sem = 1; $sem <= $semesterCount; $sem++)
            $html .= "<th colspan={$fieldCount}>{$sem} семестр</th>";

        $html .= '<tr>';
        foreach ($studies->getSchedule() as $scheduleRecord) {
            $html .= "
                <td>{$scheduleRecord->getNumber()}</td>
                <td>{$scheduleRecord->getTitle()}</td>
            ";
            foreach ($scheduleRecord->getSemesters() as $semester)
                foreach ($semester as $field)
                    $html .= "<td>{$field}</td>";
            $html .= "</tr>";
        }
        return $html . '</table>';
    }
}