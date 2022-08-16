<?php

class View {
    function generate($content_view, $template, $view_data=null) {
        $template_view = $template . ".php";

        $template_model = "model_" . $template;
        $template_model_path = "app/models/" . $template_model . ".php";
        if (file_exists($template_model_path)) {
            include $template_model_path;
            $model = new $template_model;
        }


        include "app/views/" . $template_view;
    }
}