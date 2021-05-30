<?php require_once('header.view.php');
require_once(__DIR__ . '/../models/stat.model.php');

class StatView {
    /** @param Stat[] $stats */
    public static function render(array $stats, int $currentPage, int $pageCount): string {
        $html = HeaderView::render("Статистика посещений");
        $html .= "<section class='card'><h2>Статистика посещений</h2><table>";

        $pageInfo = "<table><tr><td>Страница</td>";

        for ($i = 1; $i <= $pageCount; $i++) {
            if ($currentPage === $i)
                $pageInfo .= "<td><b><a href=\"/stat?page=$i\">$i</a></b></td>";
            else
                $pageInfo .= "<td><a href=\"/stat?page=$i\">$i</a></td>";
        }
        $pageInfo .= "</tr></table><table>";
        $html .= $pageInfo;

        $html .= "
            <tr><td class='th'>ID</td>
               <td class='th'>Временной штамп</td>
               <td class='th'>Страница</td>
               <td class='th'>IP клиента</td>
               <td class='th'>Имя клиента</td>
               <td class='th'>Используемый браузер</td></tr>
        ";

        foreach ($stats as $stat) {
            $html .= "<tr><td>#{$stat->getId()}</td>
                           <td>{$stat->getTimestamp()}</td>
                           <td>{$stat->getUri()}</td>
                           <td>{$stat->getIp()}</td>
                           <td>{$stat->getHost()}</td>
                           <td>{$stat->getBrowser()}</td></tr>
                           ";
        }

        $html .= $pageInfo;

        return "</table>" . "</section>" . $html;
    }
}