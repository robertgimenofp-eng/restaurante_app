<?php


class AuthController
{

    // ACCIÃ“N 1: Solo sirve para MOSTRAR el formulario (GET)
    public function showLogin()
    {
        // 1. Definimos la vista INTERNA
        $view = 'views/auth/login.php';
        // 2. Cargamos el LAYOUT (que contiene html, head, navbar y footer)
        // Al hacer require aquÃ­, main.php tendrÃ¡ acceso a la variable $view de arriba
        require_once __DIR__ . '/../views/main.php';
    }

    // ACCIÃ“N 2: Solo sirve para PROCESAR los datos (POST)
    // El Router llamarÃ¡ aquÃ­ cuando el formulario se envÃ­e

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Recoger datos
            $email = $_POST['email'] ?? '';
            $password = $_POST['contrasena'] ?? '';

            // 2. Llamar al modelo
            require_once __DIR__ . '/../models/UsuarioDAO.php';
            $userModel = new UsuarioDAO();

            $usuario = $userModel->getByEmail($email);

            // 3. Verificar contraseña
            if ($usuario && password_verify($password, $usuario->getPassword())) {

                // LOGUEO EXITOSO
                $_SESSION['identity'] = $usuario;

                // Comprobamos si hay productos en el carrito esperando
                if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) >= 1) {
                    header("Location: index.php?controller=Carrito&action=checkout");
                } else {
                    header("Location: index.php?controller=Home&action=index");
                }
            } else {
                // Si falla, guardamos el error y volvemos a mostrar la vista
                $error = "Credenciales incorrectas";
                $view = 'views/auth/login.php';
                require_once __DIR__ . '/../views/main.php';
            }
        } else {
            // Si entra por GET a esta URL, mostramos el login normal
            $this->showLogin();
        }
    }

    // REGISTRO
    public function showRegister()
    {
        // 1. Definimos el contenido
        $view = 'views/auth/register.php';
        // 2. Cargamos el Layout
        require_once __DIR__ . '/../views/main.php';
    }

    // 2. Procesa los datos (POST)
    public function register()
    {
        // Verificar si vienen datos por POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            require_once __DIR__ . '/../models/UsuarioDAO.php';
            $userModel = new UsuarioDAO();

            // 2.1 Recoger datos del formulario
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $password = $_POST['contrasena'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];

            // 2.2 Comprobar si el email ya existe
            if ($userModel->getByEmail($email)) {
                $error = "Ese email ya està registrado";
                $view = 'views/auth/register.php';
                require_once __DIR__ . '/../views/main.php';
                return;
            }

            // 2.3 ENCRIPTAR LA CONTRASEÑA
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
                // Redirigimos al Login para que entre
                header("Location: index.php?controller=Auth&action=showLogin");
            } else {
                // ERROR: Algo fallÃ³ en SQL
                $error = "Error al guardar el usuario.";
                $view = 'views/auth/register.php';
                require_once __DIR__ . '/../views/main.php';
            }
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php?controller=Auth&action=showLogin");
    }
}
?>