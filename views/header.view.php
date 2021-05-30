<?php

class HeaderView {
    public static function render(string $title): string {
        $html = <<<HTML
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>{$title}</title>
                <link rel="stylesheet" href="/styles/style.css" type="text/css">
                <link rel="stylesheet" href="/styles/calendar.css" type="text/css">
                <script src="/scripts/jquery.js"></script>
                <script src="/scripts/navigation.js"></script>
                <script src="/scripts/photos.js"></script>
                <script src="/scripts/current_time.js"></script>
                <script src="/scripts/history_tracking.js"></script>
                <script src="/scripts/form_verification.js"></script>
                <script src="/scripts/popover.js"></script>
                <script src="/scripts/window.js"></script>
                <script src="/scripts/calendar.js"></script>
            </head>
            <body>
            <header class="dark-background">
                <h1 class="site-title">Личный сайт</h1>
                <p id="header-date" class="dark-background"> </p>
            </header>
            <nav class="flex-row-container">
        HTML;

        if ($title === 'Главная')
            $html .= '<div>
                        <img id="index" src="/icons/index-checked.png" alt="">
                        <a href="/" class="active">Главная</a>
                      </div>';
        else
            $html .= '<div>
                        <img id="index" src="/icons/index.png" alt="">
                        <a href="/">Главная</a>
                      </div>';

        if ($title === 'Обо мне')
            $html .= '<div>
                          <img id="about" src="/icons/about-checked.png" alt="">
                          <a href="/bio" class="active">Обо мне</a>
                      </div>';
        else
            $html .= '<div>
                          <img id="about" src="/icons/about.png" alt="">
                          <a href="/bio">Обо мне</a>
                      </div>';

        if ($title === 'Интересы')
            $html .= '<div>
                          <img id="interests" src="/icons/interests-checked.png" alt="">
                          <a href="/interests" class="active">Интересы</a>
                      </div>';
        else
            $html .= '<div>
                          <img id="interests" src="/icons/interests.png" alt="">
                          <a href="/interests">Интересы</a>
                      </div>';

        if ($title === 'Учеба')
            $html .= '<div>
                        <img id="studies" src="/icons/studies-checked.png" alt="">
                        <a href="/studies" class="active">Учёба</a>
                      </div>';
        else
            $html .= '<div>
                        <img id="studies" src="/icons/studies.png" alt="">
                        <a href="/studies">Учёба</a>
                      </div>';

        if ($title === 'Альбом')
            $html .= '<div>
                        <img id="photos" src="/icons/photos-checked.png" alt="">
                        <a href="/photos" class="active">Альбом</a>
                      </div>';
        else
            $html .= '<div>
                        <img id="photos" src="/icons/photos.png" alt="">
                        <a href="/photos">Альбом</a>
                      </div>';

        if ($title === 'Гостевая книга')
            $html .=  '<div>
                         <img id="contact" src="/icons/contact-checked.png" alt="">
                         <a href="/contact" class="active">Гостевая книга</a>
                       </div>';
        else
            $html .=  '<div>
                         <img id="contact" src="/icons/contact.png" alt="">
                         <a href="/contact">Гостевая книга</a>
                       </div>';

        if ($title === 'Тест')
            $html .=  '<div>
                          <img id="test" src="/icons/test-checked.png" alt="">
                          <a href="/test" class="active">Тест</a>
                       </div>';
        else
            $html .=  '<div>
                         <img id="test" src="/icons/test.png" alt="">
                         <a href="/test">Тест</a>
                       </div>';

        return $html . '</nav>';
    }
}