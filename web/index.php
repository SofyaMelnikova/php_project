<?php

use app\controllers\AboutController;
use app\controllers\MainController;
use app\controllers\MainPageController;
use app\controllers\PostController;
use app\controllers\ProfileController;
use app\controllers\RegistrationController;
use app\controllers\WelcomeController;
use app\core\Application;
use app\core\ConfigParser;
use app\core\IniConfigParser;
use app\core\Request;
use app\exceptions\RouteException;

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|html?|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}
const PROJECT_DIR = __DIR__ . "/../";
require PROJECT_DIR . "/vendor/autoload.php";

spl_autoload_register(function ($classname) {
    require str_replace("app\\", PROJECT_DIR, $classname) . ".php";
});

date_default_timezone_set("Europe/Moscow");

ConfigParser::load();
IniConfigParser::loadSection("Main");

$app_env = getenv("APP_ENV");
if ($app_env == "dev") {
    error_reporting(E_ALL);
    ini_set("display_errors", "1");
    ini_set("log_errors", "1");
    ini_set("error_log", PROJECT_DIR . getenv("PHP_LOG_ROOT"));
}

$application = new Application();
//$router = $application->getRouter();


try {
    $application->setRoute(Request::GET, "/about", [new AboutController(), "getView"]);

    $application->setRoute(Request::GET, "/", [new WelcomeController(), "getWelcomeView"]);
    $application->setRoute(Request::GET, "/main", [new MainPageController(), "getMainView"]);
    $application->setRoute(Request::GET, "/profile", [new ProfileController(), "getProfileView"]);
    $application->setRoute(Request::GET, "/make_post", [new PostController(), "getPostFormView"]);

    $application->setRoute(Request::GET, "/search", [new PostController(), "searchByTitle"]);
    $application->setRoute(Request::GET, "/filter", [new PostController(), "filterByGenre"]);

    $application->setRoute(Request::POST, "/make_post", [new PostController(), "createPost"]);

    $application->setRoute(Request::GET, "/post", [new MainPageController(), "getPostView"]);
    $application->setRoute(Request::GET, "/anon_post", [new WelcomeController(), "getPostView"]);
    $application->setRoute(Request::GET, "/like", [new ProfileController(), "saveLike"]);
    $application->setRoute(Request::GET, "/unlike", [new ProfileController(), "removeLike"]);
    $application->setRoute(Request::POST, "/comment", [new PostController(), "saveComment"]);

    $application->setRoute(Request::GET, "/login", [new RegistrationController(), "getLoginView"]);
    $application->setRoute(Request::POST, "/login", [new RegistrationController(), "handleLoginView"]);

    $application->setRoute(Request::GET, "/profile/my_likes", [new ProfileController(), "getProfileLikesView"]);
    $application->setRoute(Request::GET, "/profile/my_posts", [new ProfileController(), "getProfilePostsView"]);
    $application->setRoute(Request::GET, "/profile/delete", [new ProfileController(), "removePost"]);

    $application->setRoute(Request::GET, "/sign_up", [new RegistrationController(), "getSignUpView"]);
    $application->setRoute(Request::POST, "/sign_up", [new RegistrationController(), "handleSignUpView"]);

    $application->setRoute(Request::GET, "/profile/edit", [new ProfileController(), "getProfileEditView"]);
    $application->setRoute(Request::POST, "/profile/edit", [new ProfileController(), "handleProfileEditView"]);

    $application->setRoute(Request::GET, "/user", [new MainPageController(), "getUserView"]);



} catch (RouteException $e) {
    //log
    exit;
}


ob_start();
$application->run();
ob_flush();