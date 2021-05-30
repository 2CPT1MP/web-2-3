<?php
require_once(__DIR__ . "/../../core/routing/controller.core.php");
require_once(__DIR__ . "/../../views/contact.view.php");

class ContactController extends RestController {

    public function GET(Request $request): string {
        return ContactView::render(GuestBookItem::findAll());
    }

    public function POST(Request $request): string {
        return MessageView::render("Ошибка", "Неверное использование");
    }
}