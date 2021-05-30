<?php require_once(__DIR__ . '/../header.view.php');

class UploadMessagesView {
    public static function render(): string {
        $html = HeaderView::render('Загрузка сообщений');
        $html .= '<section class="card">';
        return $html . <<<MESSAGES
            <h2>Загрузить файл сообщений</h2>
            <article class="flex-container card">
                <form id="contact-form" action="/contact/messages" method=POST autocomplete="off" enctype="multipart/form-data">
                    <label id="file-label">Выберите файл сообщений<br></label>
                    <input id="messages" name="messages" type="file" required autocomplete="off" accept=".inc">
                    <button id='submit-btn' type="submit">Загрузить</button>
                </form>
            </article>
        MESSAGES;
    }
}