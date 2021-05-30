<?php
require_once(__DIR__ . "/../../core/routing/controller.core.php");
require_once(__DIR__ . "/../../core/io/file-uploader.core.php");
require_once(__DIR__ . "/../../views/guest-book/upload-messages.view.php");
require_once(__DIR__ . "/../../views/login.view.php");
require_once(__DIR__ . "/../../modules/form-validators/guest-message.validator.php");

class MessagesController extends RestController {

    private function validateMessagesFile(string $fileName): bool {
        $lines = file($fileName, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        $emptyFile = !$lines || count($lines) < 1;

        if ($emptyFile)
            return false;

        foreach ($lines as $line) {
            $fields = explode(';', $line);
            if (count($fields) !== 9)
                return false;

            $validator = new GuestMessageValidator();
            $result = $validator->validate($fields);

            if (!$result->isValid())
                return false;
        }
        return true;
    }

    private function uploadMessagesFile(): bool {
        $fileNotAttached = !isset($_FILES["messages"]) || !isset($_FILES["messages"]["tmp_name"]);
        if ($fileNotAttached)
            return false;

        $file = $_FILES["messages"]["tmp_name"];
        if (!$this->validateMessagesFile($file))
            return false;

        return copy($file, __DIR__ . '/../../files/messages.inc');
    }

    public function GET(Request $request): string {
        if ($this->checkAuthorization($request))
            return UploadMessagesView::render();
        return LoginView::render('/contact/messages');
    }

    public function POST(Request $request): string {
        if (!$this->checkAuthorization($request))
            return LoginView::render('/contact/messages');
        if (!$this->uploadMessagesFile())
            return MessageView::render("Ошибка", "При попытке загрузки файла произошла ошибка");
        return MessageView::render("Файл загружен", "Файл был успешно загружен");
    }

    public static function checkAuthorization(Request $request): bool {
        session_start();
        var_dump('<pre>', $_SERVER['REQUEST_TIME'], '</pre>');
        var_dump('<pre>', $_SERVER['REQUEST_URI'], '</pre>');
        var_dump('<pre>', $_SERVER['REMOTE_ADDR'], '</pre>');
        var_dump('<pre>', $_SERVER['REMOTE_HOST'], '</pre>');
        var_dump('<pre>', $_SERVER['HTTP_USER_AGENT'], '</pre>');

        //var_dump('<pre>', $_SERVER, '</pre>');

        if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
            http_response_code(401);
            return false;
        }
        return true;
    }
}