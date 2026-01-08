<?php
echo "Hola, el servidor está vivo"; die();
// index.php
session_start();
// Cargar controladores
$controllerName = isset($_GET['controller']) ? $_GET['controller'] . 'Controller' : 'HomeController';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// Rutas a los archivos
$controllerPath = 'controllers/' . $controllerName . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();

    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        echo "Error: La acción no existe.";
    }
} else {
    echo "Error: El controlador no existe.";
}
?>