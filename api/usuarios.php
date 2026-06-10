<?php
require_once '../models/Usuario.php';
require_once '../models/UsuarioDAO.php';
session_start();

// Validar que el usuario sea admin
if (!isset($_SESSION['identity']) || $_SESSION['identity']->getRol() !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}

$metodo = $_SERVER['REQUEST_METHOD'];
$usuarioDAO = new UsuarioDAO();

if ($metodo === 'GET') {
    $usuarios = $usuarioDAO->getAll();
    echo json_encode($usuarios);
} elseif ($metodo === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if(isset($input['action'])) {
        if ($input['action'] === 'update') {
            $id = $input['id_usuario'];
            $datos = [
                'nombre' => $input['nombre'],
                'email' => $input['email'],
                'telefono' => $input['telefono'],
                'direccion' => $input['direccion'],
                'rol' => $input['rol']
            ];
            $success = $usuarioDAO->update($id, $datos);
            echo json_encode(['success' => $success]);
        } elseif ($input['action'] === 'delete') {
            $id = $input['id_usuario'];
            $success = $usuarioDAO->delete($id);
            echo json_encode(['success' => $success]);
        }
    }
}
?>
