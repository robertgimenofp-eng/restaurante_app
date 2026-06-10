<?php
// API REST para gestiÃ³n de productos
session_start();

require_once '../controllers/ProductoController.php';
$controller = new ProductoController();

$metodo = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($metodo === 'GET') {
    if ($action === 'borrar') { // borrado via GET temporal en el front
        $controller->borrar();
    } elseif (isset($_GET['id'])) {
        $controller->apiObtener();
    } else {
        $controller->apiListar();
    }
} elseif ($metodo === 'POST') {
    if ($action === 'save') {
        $controller->save();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'AcciÃ³n no vÃ¡lida']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'MÃ©todo no permitido']);
}
?>
