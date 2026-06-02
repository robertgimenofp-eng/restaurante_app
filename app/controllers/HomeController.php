<?php

class HomeController {
    public function index() {
        require_once 'app/models/OfertaDAO.php';
        $ofertaDAO = new OfertaDAO();
        $oferta_activa = $ofertaDAO->getOfertaActivaAssoc();

        // 3. VISTA
        $view = "views/home/home.php";
        require_once __DIR__ . "/../views/main.php";
    }
}
