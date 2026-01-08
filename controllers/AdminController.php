<?php
require_once 'models/Log.php';
class AdminController {

    // Método para verificar si es admin
    // Lo ponemos private porque solo lo usaremos dentro de esta clase
    private function verificarAdmin() {
        // 1. ¿Está logueado?
        if (!isset($_SESSION['identity'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit();
        }

        // 2. ¿Es admin? (Asumiendo que guardamos el rol en la sesión al loguear)
        // Nota: Tendremos que revisar tu AuthController para asegurarnos de que guarda el rol.
        if ($_SESSION['identity']->rol != 'admin') {
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
    if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
        exit();
    }

    require_once 'config/db.php';
    require_once 'models/Log.php';
    $db = Database::connect();
    $logModel = new Log($db);
    
    $logs = $logModel->getAll();

    header('Content-Type: application/json');
    echo json_encode($logs);
    exit();
}
}

?>