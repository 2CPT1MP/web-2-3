<?php require_once('../views/photos.view.php');

class PhotosController extends RestController {
    private function showPhotos(array $photos): string {
        return PhotosView::render($photos);
    }

    private function sendImage(int $id): bool {
        header('Content-Type: image/png');
        return readfile("../images/backgrounds/$id.png");
    }

    public function GET(Request $request): string {
        if (isset($request->getParams()["id"])) {
            $res = $this->sendImage($request->getParams()["id"]);
            return ($res)? "" : "Картинка не найдена";
        }
        $student = new Student();
        return $this->showPhotos($student->getPhotos());
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}