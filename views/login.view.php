<?php require_once('header.view.php');

class LoginView {
    public static function render(string $redirectedFrom): string {
        $html = HeaderView::render('Авторизация');

        return $html . <<<CONTACT
            <section class="card">
            <h2>Авторизация</h2><article class=''>
            <article class="flex-container card">
                <form id="contact-form" action="/login" method=POST autocomplete="off">
                    <label id="username">Логин
                        <input name="username" id="username" type="text" required autocomplete="off">
                    </label>
                    <label id="password">Пароль
                        <input name="password" id="password" type="password" required autocomplete="off">
                    </label>
                    <input type="hidden" name="redirectedFrom" value="{$redirectedFrom}">
                    <button type="submit">Войти</button>
                </form>
            </article>
        CONTACT;
    }
}