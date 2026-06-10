<?php
// API REST para logs
session_start();

require_once '../controllers/AdminController.php';
$controller = new AdminController();

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    $controller->apiListarLogs();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'MÃ©todo no permitido']);
}
?>
