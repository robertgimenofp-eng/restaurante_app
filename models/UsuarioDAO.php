<?php
require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/Usuario.php';

class UsuarioDAO
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getByEmail($email)
    {
        $sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return false;

        $clase = ($row['rol'] === 'admin') ? 'Admin' : 'Cliente';
        $usuario = new $clase();
        $usuario->setId_usuario($row['id_usuario']);
        $usuario->setNombre($row['nombre']);
        $usuario->setEmail($row['email']);
        $usuario->setPassword($row['contraseña']);
        $usuario->setTelefono($row['telefono']);
        $usuario->setDireccion($row['direccion']);
        $usuario->setRol($row['rol']);
        $usuario->setFecha_registro($row['fecha_registro']);
        
        return $usuario;
    }

    public function create($datos)
    {
        try {
            $sql = "INSERT INTO usuario (nombre, email, contraseña, telefono, direccion, rol, fecha_registro)
                    VALUES (:nombre, :email, :password, :telefono, :direccion, 'cliente', NOW())";

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':password', $datos['password']);
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':direccion', $datos['direccion']);

            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }
}
?>