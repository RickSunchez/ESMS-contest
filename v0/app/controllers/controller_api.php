<?php

class Controller_Api extends Controller {
    function __construct($model=true) {
        if ($model) $this -> model = new Model_Api();
        $this -> view = new View();
    }

    function action_index() {
        $this -> view -> generate(
            $this -> index(), 
            "template_api"
        );
    }

    function action_create() {
        $raw_data = $this -> model -> create_data();
        if (!$raw_data) {
            $this -> view -> generate(
                $this -> error("missing fields"), 
                "template_api");
        } else {
            $this -> view -> generate(
                $this -> success(), 
                "template_api");
        }
    }

    function action_read() {
        $raw_data = $this -> model -> read_data();
        $json = $this -> fetch_data($raw_data);
        $this -> view -> generate(
            $this -> success($json), 
            "template_api"
        );
    }

    function action_update() {
        $raw_data = $this -> model -> update_data();
        if (!$raw_data) {
            $this -> view -> generate(
                $this -> error("missing fields"), 
                "template_api");
        } else {
            $this -> view -> generate(
                $this -> success(), 
                "template_api");
        }
    }

    function action_delete() {
        $raw_data = $this -> model -> delete_data();
        if (!$raw_data) {
            $this -> view -> generate(
                $this -> error("missing fields"), 
                "template_api");
        } else {
            $this -> view -> generate(
                $this -> success(), 
                "template_api");
        }
    }

    function action_ready() {
        $this -> view -> generate(
            $this -> success(file_exists("data/onload.flag")), 
            "template_api");
    }
    function action_init() {
        $this -> view -> generate(
            $this -> success(file_exists("data/onload.flag")), 
            "template_api");
    }

    public function fetch_data($raw) {
        $result = array();
        
        while ($row = $raw -> fetchArray(SQLITE3_ASSOC)) {
            $result[] = array(
                "id" => $row["Id"],
                "title" => $row["Title"],
                "developer" => $row["Developer"],
                "tags" => json_decode($row["Tags"])
            );
        }

        return $result;
    }

    public function fetch($raw) {
        $result = array();

        while ($row = $raw -> fetchArray(SQLITE3_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }

    private function error($message) {
        return array(
            "ok" => false,
            "message" => $message
        );
    }

    private function success($message="") {
        return array(
            "ok" => true,
            "responce" => $message
        );
    }

    private function index() {
        return array(
            "ok" => true,
            "responce" => array(
                "methods" => array(
                    "create" => array(
                        "method" => "GET",
                        "required" => "t,d,ts",
                        "optional" => "",
                        "example" => "/create?t=title&d=dev&ts=a,b,c"
                    ),
                    "read" => array(
                        "method" => "GET",
                        "required" => "",
                        "optional" => "id,t,d,ts",
                        "example" => "/read?id=1"
                    ),
                    "update" => array(
                        "method" => "GET",
                        "required" => "id",
                        "optional" => "t,d,ts",
                        "comments" => "required id + one of optional",
                        "example" => "/update?id=1&t=new_title"
                    ),
                    "delete" => array(
                        "method" => "GET",
                        "required" => "id",
                        "optional" => "",
                        "example" => "/delete?id=1"
                    )
                ),
                "params" => array(
                    "id" => array(
                        "name" => "Id",
                        "type" => "number",
                        "description" => "Item id"
                    ),
                    "t" => array(
                        "name" => "Title",
                        "type" => "text",
                        "description" => "Game title"
                    ),
                    "d" => array(
                        "name" => "Developer",
                        "type" => "text",
                        "description" => "Game developer"
                    ),
                    "ts" => array(
                        "name" => "Tags",
                        "type" => "text, delimited by comma",
                        "description" => "Game genres"
                    ),
                ),
                "answers" => array(
                    "success" => array(
                        "ok" => true,
                        "responce" => array()
                    ),
                    "error" => array(
                        "ok" => false,
                        "message" => ""
                    )
                )
            )
        );
    }
}