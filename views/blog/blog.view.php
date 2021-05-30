<?php use JetBrains\PhpStorm\Pure;

require_once(__DIR__ . '/../header.view.php');

class BlogView {
    /**@param BlogMessage[] $messages  */
    public static function render(array $messages, int $pageCount, int $currentPage): string {
        $html = HeaderView::render('Редактор блога');
        $html .= '<section class="card">';

        $msgs = "<h2>Оставленные сообщения</h2><article class=''>";
        $msgs .= "<b><a href='/blog/editor'>Добавить запись</b><br>";
        $msgs .= "<b><a href='/blog/messages'>Загрузить список записей</a></b>";
        $pageInfo = "<table><tr><td>Страница</td>";

        for ($i = 1; $i <= $pageCount; $i++) {
            if ($currentPage === $i)
                $pageInfo .= "<td><b><a href=\"/blog?page=$i\">$i</a></b></td>";
            else
                $pageInfo .= "<td><a href=\"/blog?page=$i\">$i</a></td>";
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

        return $html . <<<EDITOR
            $msgs
            $pageInfo
        EDITOR;
    }
}