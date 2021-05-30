<?php require_once(__DIR__ . '/../header.view.php');

class UploadBlogMessagesView {
    public static function render(): string {
        $html = HeaderView::render('Загрузка записей блога');
        $html .= '<section class="card">';
        return $html . <<<BLOGDATA
            <h2>Загрузить файл записей блога</h2>
            <article class="flex-container card">
                <form id="contact-form" action="/blog/messages" method=POST autocomplete="off" enctype="multipart/form-data">
                    <label for="blog-data" id="file-label">Выберите файл записей блога<br></label>
                    <input id="blog-data" name="blog-data" type="file" required autocomplete="off" accept=".csv">
                    <button id='submit-btn' type="submit">Загрузить</button>
                </form>
            </article>
        BLOGDATA;
    }
}