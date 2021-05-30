<?php use JetBrains\PhpStorm\Pure;

require_once(__DIR__ . '/../header.view.php');

class BlogEditorView {
    /**@param BlogMessage[] $messages  */
    public static function render(array $messages, int $pageCount, int $currentPage): string {
        $html = HeaderView::render('Редактор блога');
        $html .= '<section class="card">';

        $msgs = "<h2>Оставленные сообщения</h2><article class=''>";
        $pageInfo = "<table><tr><td>Страница</td>";

        for ($i = 1; $i <= $pageCount; $i++) {
            if ($currentPage === $i)
                $pageInfo .= "<td><b><a href=\"/blog/editor?page=$i\">$i</a></b></td>";
            else
                $pageInfo .= "<td><a href=\"/blog/editor?page=$i\">$i</a></td>";
        }
        $pageInfo .= "</tr></table>";
        $msgs .= $pageInfo;

        foreach ($messages as $message) {
            $hasImg = $message->hasImage();
            $imagePath = ($hasImg)? $message->getImagePath() : "";

            $msgs .= "
               <div class='msg-block'>
                    <img src='/blog/userImage?id=$imagePath' width='50px' alt='Нет изображения'><br>
                    <p class='no-margin'>{$message->getTimestamp()}</p>
                    <b>{$message->getTopic()}</b><br>
                    <p class='no-margin'>{$message->getText()}</p>
                   
               </div>
            ";
        }

        $msgs .= "</article>";

        //var_dump('<pre>', $messages, '</pre>');

        return $html . <<<EDITOR
            <article class="flex-container card">
            <h2>Добавить запись блога</h2>
                <form id="contact-form" action="/blog/editor" method=POST autocomplete="off" enctype="multipart/form-data">
                    <label for="topic" id="fio-label">Тема сообщения</label>
                    <input name="topic" id="topic" type="text" required autocomplete="off">

                    <label for="image" id="email-label">Прикрепить изображение</label>
                    <input name="image" id="image" type="file" accept="image/x-png,image/gif,image/jpeg">
                    
                    <label for="text">Текст сообщения</label>
                    <textarea id="text" name="text" rows="10" cols="50" required></textarea>
                    
                    <button id='submit-btn' type="submit" onsubmit="">Submit</button>
                </form>
            </article>
            $msgs
            $pageInfo
        EDITOR;
    }
}