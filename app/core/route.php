<?php

class Route {
    static function start() {
        $routes = explode("/", $_SERVER["REQUEST_URI"]);

        $controller_name = (!empty($routes[1]))
            ? explode("?", $routes[1])[0]
            : "Main";

        $action_name = (!empty($routes[2]))
            ? explode("?", $routes[2])[0]
            : "index";

        $redy_event = $controller_name == "api" && $action_name == "ready";

        if (
            !file_exists("data/onload.flag") && 
            $controller_name != "load" &&
            $controller_name != "api"
        ) {
            Route::load();
            return;
        }

        $model_name = "Model_" . $controller_name;
        $controller_name = "Controller_" . $controller_name;
        $action_name = "action_" . $action_name;

        $model_path = "app/models/" . strtolower($model_name) . ".php";
        if (file_exists($model_path)) include $model_path;

        $controller_path = "app/controllers/" . strtolower($controller_name) . ".php";
        if (file_exists($controller_path)) {
            include $controller_path;
        } else {
            Route::ErrorPage404();
            return;
        }

        if ($redy_event) {
            $controller = new $controller_name(false);
        } else {
            $controller = new $controller_name;
        }
        if (method_exists($controller, $action_name)) {
            $controller -> $action_name();
        } else {
            Route::ErrorPage404();
            return;
        }
    }

    static function ErrorPage404() {
        $host = "http://" . $_SERVER["HTTP_HOST"] . "/";
        header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		header("Location:" . $host . "404");
    }

    static function load() {
        $host = "http://" . $_SERVER["HTTP_HOST"] . "/";
        header("Location:" . $host . "load");
    }
}