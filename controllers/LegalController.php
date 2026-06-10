<?php
class LegalController {
    // Una sola acciÃ³n que carga la vista con todas las secciones
    public function index() {
        // Podemos capturar quÃ© pestaÃ±a abrir por defecto si quieres (opcional)
        // $seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'aviso';
        $view = "views/legal/index.php";
        require_once __DIR__ . '/../views/main.php';
    }
}
?>