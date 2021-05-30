<?php require_once('header.view.php');

class AdminView {
    public static function render(): string {
        $html = HeaderView::render('Администрирование');
        $html .= '<section class="card">';

        $html .= "<h2>Администрирование</h2>";

        $html .= "<b><a href='/stat'>Статистика посещений</a><br></b>";
        $html .= "<b><a href='/contact/messages'>Загрузка сообщений гостевой книги</a><br></b>";
        $html .= "<b><a href='/blog/messages'>Загрузка записей блога</a><br></b>";


        return $html . '</section>';
    }
}