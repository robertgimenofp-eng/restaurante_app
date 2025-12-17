<?php

class HomeController {
    public function index() {
        $view = "views/home/home.php";
        require_once __DIR__ . "/../views/main.php";
    }
}
