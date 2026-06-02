<?php
require_once __DIR__ . '/../config/db.php';

class UsuarioDAO {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create($datos) {
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