<?php

namespace app\controllers;

use app\core\Application;
use app\core\Request;

class AboutController
{
    public function getView(Request $request): void
    {
        Application::$app->getRouter()->renderView("about");
    }

    public function handleView(Request $request): void
    {
        var_dump($request->getBody());
    }
}