<?php
require_once('../controllers/contact.controller/contact.controller.php');
require_once('../controllers/contact.controller/contact-verifier.controller.php');
require_once('../controllers/contact.controller/messages.controller.php');

class ContactRouter extends Router {
    public function __construct() {
        $this->addController('/', new ContactController());
        $this->addController('/verify', new ContactVerifierController());
        $this->addController('/messages', new MessagesController());
    }
}