<?php require_once('header.view.php');

class HistoryView {
    public static function render(): string {
        $header = HeaderView::render('История');
        return <<<HISTORY
            {$header}
            <main>
                <section class="card">
                    <table class="border-table" id="log-table">
                        <tr class="th">
                            <th>Веб-страница</th>
                            <th>Число посещений (localStorage)</th>
                            <th>Число посещений (Cookies)</th>
                        </tr>
                    </table>
                    <button type="button" id="localStorage-clear">Очистить localStorage</button>
                </section>
            </main>
        HISTORY;
    }
}