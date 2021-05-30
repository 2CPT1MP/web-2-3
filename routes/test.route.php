<?php require_once('../controllers/test.controller/test.controller.php');
require_once('../controllers/test.controller/test-result.controller.php');

class TestRouter extends Router {
    public function __construct() {
        $this->addController('/', new TestController());
        $this->addController('/verify', new TestVerifierController());
        $this->addController('/result', new TestResultController());
    }
}