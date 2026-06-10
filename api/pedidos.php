<?php
// API REST para gestiÃ³n de pedidos
require_once '../models/Usuario.php';
session_start();

// Simular el controlador frontal para cargar la lÃ³gica MVC de forma limpia
require_once '../controllers/PedidoController.php';
$controller = new PedidoController();

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        if (isset($_GET['id'])) {
            $controller->apiDetalles();
        } else {
            $controller->apiListar();
        }
        break;
    case 'POST':
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        if ($action === 'cambiarEstado') {
            $controller->apiCambiarEstado();
        } elseif ($action === 'eliminar') {
            $controller->apiEliminar();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'AcciÃ³n no vÃ¡lida']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'MÃ©todo no permitido']);
}
?>
