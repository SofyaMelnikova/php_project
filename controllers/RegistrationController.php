<?php

declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\core\Response;
use app\mappers\UserMapper;
use app\models\User;
use Exception;

class RegistrationController
{
    private static string $DEFAULT_PHOTO = "../web/img/profilePhoto.jpg";

    public function getLoginView(Request $request): void
    {
        Application::$app->getRouter()->renderTemplate("login.html", ["action" => "login"]);
    }

    public function handleLoginView(Request $request): void
    {
        try {
            $userMapper = new UserMapper();

            $body = $request->getBody();
            $user = $userMapper->SelectByEmail($body["email"]);

            if ($user != null) {
//                 TODO compare ENCODE! passwords
                if (password_verify($body["password"], $user->getPassword()) == 0) {
                    setcookie("userId", (string) $user->getId());

                    Application::$app->getRouter()->renderTemplate("profile.html", ["user" => $user]);
                } else {
                    // TODO show errors
                    Application::$app->getRouter()->renderTemplate("login.html", ["passwordErr" => "You entered wrong password"]);
                }

            } else {
                Application::$app->getRouter()->renderTemplate("sign_up.html", ['action'=>'sign_up']);
            }
        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }

    public function getSignUpView(Request $request): void
    {
        Application::$app->getRouter()->renderTemplate("sign_up.html", ["action" => "sign_up"]);
    }

    public function handleSignUpView(Request $request): void
    {
        $user = null;
        try {
            $mapper = new UserMapper();
            $body = $request->getBody();

            if (strcmp($body["password"], $body["passwordConfirm"]) == 0) {

                $users = $mapper->SelectByEmail($body["email"]);
                if ($users != null) {
                    // TODO show errors
                    Application::$app->getRouter()->renderTemplate("login.html", ["userExist" => "User with such email exists"]);
                }

                $hashPass = password_hash($body["password"], PASSWORD_BCRYPT);
                // TODO encode password
                $user = $mapper->createObject(["username" => $body["username"], "email" => $body["email"], "password" => $hashPass, "photo" => self::$DEFAULT_PHOTO, "description" => ""]);
                $mapper->Insert($user);
                setcookie("userId", (string) $user->getId());

                Application::$app->getRouter()->renderTemplate("profile.html", ["user" => $user]);
            } else {
                Application::$app->getRouter()->renderTemplate("sign_up.html", ["passwordErr" => "Repeated password does not match"]);
            }

        } catch (Exception $e) {
            Application::$app->getLogger()->error($e);
            Application::$app->getResponse()->setStatusCode(Response::HTTP_SERVER_ERROR);
        }
    }
}