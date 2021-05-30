<?php require_once('header.view.php');

class IndexView {
    public static function render($student): string {
        $header = HeaderView::render('Главная');
        return <<<INDEX
            {$header}
            <main>
                <article class="card">
                    <img src="img/Steam.jpg" class="profile-pic" id="pic" width="300px" alt="">
                    <h2 class="first-heading">{$student->getName()}</h2>
                    <table>
                        <tr>
                            <td class="th">Группа</td>
                            <td>{$student->getGroup()}</td>
                        </tr>
                        <tr>
                            <td class="th">Номер работы</td>
                            <td>Лабораторная работа {$student->getLabNum()}</td>
                        </tr>
                        <tr>
                            <td class="th">Название работы</td>
                            <td>{$student->getLabTitle()}</td>
                        </tr>
                    </table>
                    <b><a href="/blog">Персональный блог</a></b><br>
                    <b><a href="/admin">Администрирование</a></b><br>
                    <!--<a href="/history">Просмотр истории посещений</a>-->
                </article>
            </main>
        INDEX;
    }
}