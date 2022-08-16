<?php

include_once "app/models/model_api.php";
include_once "app/controllers/controller_api.php";

class Model_Template_View extends Model {
    function __construct() {
        $api_model = new Model_Api();
        $api_controller = new Controller_Api();

        $raw = $api_model -> read_data();
        $this -> data = $api_controller -> fetch_data($raw);
    }
}