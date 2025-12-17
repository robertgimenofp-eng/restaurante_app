<?php
require_once 'models/User.php';

class AuthController {

    // ACCIÓN 1: Solo sirve para MOSTRAR el formulario (GET)
    // MUESTRA EL LOGIN
    public function showLogin() {
        // 1. Definimos la vista INTERNA
        $view = 'views/auth/login.php';
        // 2. Cargamos el LAYOUT (que contiene html, head, navbar y footer)
        // Al hacer require aquí, main.php tendrá acceso a la variable $view de arriba
        require_once 'views/main.php';
    }

    // ACCIÓN 2: Solo sirve para PROCESAR los datos (POST)
    // El Router llamará aquí cuando el formulario se envíe
    public function login() {
        // Ya sabemos que es POST porque el formulario apunta aquí

        // 1. Recoger datos
        $email = $_POST['email'];
        $password = $_POST['contraseña'];

        // 2. Llamar al modelo
        require_once 'config/db.php';
        $database = new Database();
        $db = $database->connect();
        $userModel = new User($db);

        $usuario = $userModel->getByEmail($email);

        // 3. Verificar
        if ($usuario && password_verify($password, $usuario->contraseña)) {
            session_start();
            $_SESSION['user_id'] = $usuario->id_usuario;
            $_SESSION['user_name'] = $usuario->nombre;
            header("Location: index.php?controller=Home&action=index");
        } else {
            // Si falla, guardamos el error y volvemos a mostrar la vista
            $error = "Credenciales incorrectas";
            $view = 'views/auth/login.php';
            require_once 'views/main.php';
        }
    }
    // Añadir en controllers/AuthController.php

    // 1. Muestra la vista (GET)
    // REGISTRO
    public function showRegister() {
        // 1. Definimos el contenido
        $view = 'views/auth/register.php';
        // 2. Cargamos el Layout
        require_once 'views/main.php';
    }

    // 2. Procesa los datos (POST)
    public function register() {
        // Verificar si vienen datos por POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            require_once 'config/db.php';
            $database = new Database();
            $db = $database->connect();
            $userModel = new User($db);

            // 2.1 Recoger datos del formulario
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $password = $_POST['contraseña'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];

            // 2.2 Comprobar si el email ya existe (Opcional pero recomendado)
            if ($userModel->getByEmail($email)) {
                $error = "Ese email ya está registrado";
                $view = 'views/auth/register.php';
                require_once 'views/main.php';
                return; // Paramos aquí
            }

            // 2.3 ENCRIPTAR LA CONTRASEÑA (Vital)
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // 2.4 Preparar datos para el modelo
            $datosUsuario = [
                'nombre' => $nombre,
                'email' => $email,
                'password' => $passwordHash,
                'telefono' => $telefono,
                'direccion' => $direccion
            ];

            // 2.5 Guardar
            if ($userModel->create($datosUsuario)) {
                // ÉXITO: Redirigimos al Login para que entre
                header("Location: index.php?controller=Auth&action=showLogin");
            } else {
                // ERROR: Algo falló en SQL
                $error = "Error al guardar el usuario.";
                $view = 'views/auth/register.php';
                require_once 'views/main.php';
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php?controller=Auth&action=showLogin");
    }
}
?>