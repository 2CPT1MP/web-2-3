<?php

/**
 * Core MVC functionality modules
 */
require_once('../core/routing/request.core.php');
require_once('../core/routing/router.core.php');

/**
 * User-defined Routers/Controllers
 */
require_once('../routes/contact.route.php');
require_once('../routes/test.route.php');
require_once('../routes/blog.route.php');
require_once('../controllers/index.controller.php');
require_once('../controllers/history.controller.php');
require_once('../controllers/bio.controller.php');
require_once('../controllers/interests.controller.php');
require_once('../controllers/auth.controller/login.controller.php');
require_once('../controllers/auth.controller/logout.controller.php');
require_once('../controllers/auth.controller/register.controller.php');
require_once('../controllers/studies.controller.php');
require_once('../controllers/photos.controller.php');
require_once('../controllers/test.controller/test-verifier.controller.php');
require_once('../controllers/stat.controller.php');
require_once('../controllers/admin.controller.php');
require_once('../core/active-record/active-record.core.php');
require_once('../models/stat.model.php');


$request = new Request();
$rootRouter = new Router();
ActiveRecord::connect();

$rootRouter->addRouter("/contact", new ContactRouter());
$rootRouter->addRouter("/test", new TestRouter());
$rootRouter->addRouter("/blog", new BlogRouter());

$rootRouter->addController('/', new IndexController());
$rootRouter->addController("/bio", new BioController());
$rootRouter->addController("/interests", new InterestsController());
$rootRouter->addController("/studies", new StudiesController());
$rootRouter->addController("/photos", new PhotosController());
$rootRouter->addController('/history', new HistoryController());
$rootRouter->addController('/login', new LoginController());
$rootRouter->addController('/logout', new LogoutController());
$rootRouter->addController('/register', new RegisterController());
$rootRouter->addController('/stat', new StatController());
$rootRouter->addController('/admin', new AdminController());


$statRecord = new Stat();
$statRecord->setIp($_SERVER['REMOTE_ADDR']);
$statRecord->setUri($_SERVER['REQUEST_URI']);
$statRecord->setBrowser($_SERVER['HTTP_USER_AGENT']);
$statRecord->save();

$res = $rootRouter->processRequest($request);
echo $res;


/**
 * !!! The code provided under this comment is intended for TESTING ONLY !!!
 */
require_once("../models/test.model/result.model.php");
require_once("../models/test.model/test.model.php");
require_once("../models/test.model/answer.model.php");
require_once("../models/test.model/test-question.model.php");

if (count(Test::findAll()) < 1) {
    $test = new Test("Тест 1");
    $q1 = new TestQuestion("JS расшифровывается как");
    $q1->addRightAnswer(new Answer("JavaScript"));
    $q1->addWrongAnswer(new Answer("JackScript", "WRONG"));
    $test->addTestQuestion($q1);

    $q2 = new TestQuestion("Допустимые имена переменных", "MULTIPLE_SELECT");
    $q2->addRightAnswer(new Answer("var1"));
    $q2->addRightAnswer(new Answer("_var1"));
    $q2->addWrongAnswer(new Answer("1_var", "WRONG"));
    $test->addTestQuestion($q2);

    $q3 = new TestQuestion("PC расшифровывается как", "RADIO");
    $q3->addRightAnswer(new Answer("Personal Computer"));
    $q3->addWrongAnswer(new Answer("Personal Calculator", "WRONG"));
    $test->addTestQuestion($q3);

    $q4 = new TestQuestion("SQL расшифровывается как", "TEXT");
    $q4->addRightAnswer(new Answer("Structured Query Language"));
    $test->addTestQuestion($q4);

    $test->save();
}



/*
 * BlogMessage Unit test
 */
/*
require_once("../models/blog-message.model.php");

$newBlogMsg = new BlogMessage();
$newBlogMsg->setTopic("Topic 1");
$newBlogMsg->setText("Text 1");
$newBlogMsg->setImagePath("/");
$newBlogMsg->save();

$id = $newBlogMsg->getId();
$res1 = BlogMessage::findById($id);
$res2 = BlogMessage::findAll();

$newBlogMsg->setText("Modded Text 1");
$newBlogMsg->save();
$res1 = BlogMessage::findById($newBlogMsg->getId());
$res2 = BlogMessage::findAll();

$status = $newBlogMsg->delete();
$status = BlogMessage::findById($id);
$status = BlogMessage::findAll();
$newBlogMsg->save();*/
/*
require_once("../core/io/file-reader.core.php");

$csvReader = new CSVFileReader("C:/Users/2CPT1/Desktop/untitled1/123.csv");
$data = $csvReader->read();
echo $data;*/

