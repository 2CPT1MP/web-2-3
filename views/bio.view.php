<?php require_once('header.view.php');

class BioView {
    public static function render(Bio $bio): string {
        $html = HeaderView::render('Обо мне');
        $html .= '<section class="card">';

        foreach ($bio->getArticles() as $value) {
            $html .= "<h3>{$value->getHeading()}</h3>";
            $html .= "<p>{$value->getText()}</p>";
        }
        return $html . '</section>';
    }
}