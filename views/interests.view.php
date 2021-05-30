<?php
require_once('header.view.php');
require_once('../models/interests.model/interests.model.php');

class InterestsView {
    public static function render(Interests $interests): string {
        $header = HeaderView::render('Интересы') . "<section class=\"card\">";
        $html = "";
        $links = "<h3>Содержание</h3><ul>";

        foreach ($interests->getInterests() as $category) {
            $html .= "<h3 id=\"{$category->getTitle()}\">{$category->getTitle()}</h3><ul>";
            $links .= "<li><a href=\"#{$category->getTitle()}\">{$category->getTitle()}</a></li>";
            foreach ($category->getListItems() as $listItem)
                $html .= "<li>{$listItem}</li>";
            $html .= '</ul>';
        }
        return $header . $links . $html . '</section>';
    }
}