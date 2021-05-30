<?php
require_once('../controllers/blog.controller.php/blog-editor.controller.php');
require_once('../controllers/blog.controller.php/blog-image.controller.php');
require_once('../controllers/blog.controller.php/blog.controller.php');
require_once('../controllers/blog.controller.php/blog-messages.controller.php');

class BlogRouter extends Router {
    public function __construct() {
        $this->addController('/', new BlogController());
        $this->addController('/editor', new BlogEditorController());
        $this->addController('/userImage', new BlogImageController());
        $this->addController('/messages', new BlogMessagesController());
    }
}