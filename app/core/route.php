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
        }

        $controller = new $controller_name;
        if (method_exists($controller, $action_name)) {
            $controller -> $action_name();
        } else {
            Route::ErrorPage404();
        }
    }

    static function ErrorPage404() {
        $host = "http://".$_SERVER["HTTP_HOST"]."/";
        header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		header("Location:".$host."404");
    }
}