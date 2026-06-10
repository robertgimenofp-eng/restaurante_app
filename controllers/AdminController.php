<?php
require_once 'models/LogDAO.php';
class AdminController {

    // MÃ©todo para verificar si es admin
    // Lo ponemos private porque solo lo usaremos dentro de esta clase
    private function verificarAdmin() {
        // 1. Â¿EstÃ¡ logueado?
        if (!isset($_SESSION['identity'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit();
        }

        // 2. Es admin? (Asumiendo que guardamos el rol en la sesiÃ³n al loguear)
        // Nota: Tendremos que revisar AuthController para asegurarnos de que guarda el rol.
        if ($_SESSION['identity']->getRol() != 'admin') {
            header("Location: index.php"); // Lo mandamos al inicio
            exit();
        }
    }

    public function index() {
        // Primero, seguridad
        $this->verificarAdmin();

        // Si pasa, mostramos la vista del panel
        require_once 'views/admin/dashboard.php';
    }
    public function apiListarLogs() {
    // Seguridad
    if (!isset($_SESSION['identity']) || $_SESSION['identity']->getRol() != 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
        exit();
    }

    require_once 'database/db.php';
    require_once 'models/LogDAO.php';
    $db = Database::connect();
    $logDAO = new LogDAO($db);
    
    $logs = $logDAO->getAll();

    header('Content-Type: application/json');
    echo json_encode($logs);
    exit();
}
}

?>