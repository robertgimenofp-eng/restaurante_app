<?php
require_once 'config/db.php';
class HomeController {
    public function index() {
        // 1. Conexión para buscar ofertas activas
        $database = new Database();
        $db = $database->connect();

        // 2. Consulta: Buscamos ofertas donde la fecha actual esté entre inicio y fin
        $hoy = date('Y-m-d');
        $sql = "SELECT * FROM oferta 
                WHERE fecha_inicio <= '$hoy' 
                AND fecha_fin >= '$hoy' 
                ORDER BY id_oferta DESC LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $oferta_activa = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. VISTA
        $view = "views/home/home.php";
        require_once __DIR__ . "/../views/main.php";
    }
}
