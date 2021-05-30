<?php
require_once(__DIR__ . "/../../core/routing/controller.core.php");
require_once(__DIR__ . "/../../core/io/file-uploader.core.php");
require_once(__DIR__ . "/../../core/io/file-reader.core.php");
require_once(__DIR__ . "/../../views/blog/upload-blog-messages.view.php");

class BlogMessagesController extends RestController {

    private function submitRecords(): bool {
        $fileNotAttached = !isset($_FILES["blog-data"]) || !isset($_FILES["blog-data"]["tmp_name"]);
        if ($fileNotAttached)
            return false;

        $file = $_FILES["blog-data"]["tmp_name"];
        $validator = new BlogMessageValidator();
        $fileReader = new CSVFileReader($file);
        BlogMessage::deleteAll();

        foreach ($fileReader->read() as $record) {
            $result = $validator->validate($record);
            if (!$result->isValid())
                continue;

            $newBlogMessage = new BlogMessage();
            $newBlogMessage->setTopic($record["topic"]);
            $newBlogMessage->setText($record["text"]);
            $newBlogMessage->setImagePath($record["imagePath"]);
            $newBlogMessage->setTimestamp($record["timestamp"]);
            $newBlogMessage->save();
        }

        return true;
    }

    public function GET(Request $request): string {
        [$authenticated, $role] = MessagesController::checkAuthorization();

        if (!$authenticated || $role !== "ADMIN")
            return LoginView::render('/blog/messages');
        return UploadBlogMessagesView::render();
    }

    public function POST(Request $request): string {
        [$authenticated, $role] = MessagesController::checkAuthorization();

        if (!$authenticated || $role !== "ADMIN")
            return LoginView::render('/blog/messages');
        if (!$this->submitRecords())
            return MessageView::render("Ошибка", "При попытке загрузки файла произошла ошибка");
        return MessageView::render("Файл загружен", "Файл был успешно загружен");
    }
}