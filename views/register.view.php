<?php require_once('header.view.php');

class RegisterView {
    public static function render(string $redirectedFrom): string {
        $html = HeaderView::render('Регистрация');

        return $html . <<<CONTACT
            <section class="card">
            <h2>Регистрация</h2><article class=''>
            <article class="flex-container card">
                <form id="contact-form" action="/register" method=POST autocomplete="off">
                    <label id="name">ФИО
                        <input name="name" id="name" type="text" required autocomplete="off">
                    </label>
                     <label id="email">Email
                        <input name="email" id="email" type="email" required autocomplete="off">
                    </label>
                    <label id="login">Логин
                        <input name="login" id="login" type="text" required autocomplete="off">
                    </label>
                    <label id="password">Пароль
                        <input name="password" id="password" type="password" required autocomplete="off">
                    </label>
                    <input type="hidden" name="redirectedFrom" value="{$redirectedFrom}">
                    <button type="submit">Зарегистрироваться</button>
                </form>
            </article>
        CONTACT;
    }
}