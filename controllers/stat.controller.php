<?php require_once('../views/stat.view.php');

class StatController extends RestController {
    /** @param Stat[] $stats */
    public function showStats(array $stats): string {
        return StatView::render($stats);
    }

    public function GET(Request $request): string {
        [$authenticated, $role] = MessagesController::checkAuthorization();

        if (!$authenticated || $role !== "ADMIN")
            header("Location: /login");

        $page = 1;
        $recordsPerPage = 10;

        if (isset($request->getParams()["page"]))
            $page = $request->getParams()["page"];

        if (isset($request->getParams()["recordsPerPage"]))
            $recordsPerPage = $request->getParams()["recordsPerPage"];

        $recordCount = Stat::getCount();
        $pageCount = Stat::getPageCount($recordsPerPage);

        $blogMessages = Stat::findAllForPage($page, $recordsPerPage);
        return StatView::render($blogMessages, $page, $pageCount);
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}

