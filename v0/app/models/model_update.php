<?php

include_once "app/models/model_api.php";
include_once "app/controllers/controller_api.php";

class Model_Update extends Model {
    function __construct() {
        $this -> api_model = new Model_Api();
        $this -> api_controller = new Controller_Api();
    }

    function get_data() {
        $raw = $this -> api_model -> read_data();
        return $this -> api_controller -> fetch_data($raw);
    }
}