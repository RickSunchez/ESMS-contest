<?php

class Controller_Load extends Controller {
    function action_index() {
        $this->view->generate("load_view.php", "template_empty");
    }
}