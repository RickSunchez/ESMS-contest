<?php

class Controller_Genres extends Controller {
    function __construct() {
        $this -> model = new Model_Genres();
		$this -> view = new View();
    }
    function action_index() {
        $data = $this -> model -> get_data();
        $this -> view -> generate("genres_view.php", "template_view", $view_data=$data);
    }
}