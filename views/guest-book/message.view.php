<?php require_once(__DIR__ . '/../header.view.php');

class MessageView {
    public static function render(string $title, string $text): string {
        $html = HeaderView::render($title);
        $html .= '<section class="card">';

        return "$html 
            <h3>$title</h3>
            <p>$text</p>
        </section>";
    }
}