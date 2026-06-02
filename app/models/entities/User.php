<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Buscar usuario por email para el login
    public function getByEmail($email) {
        // Usamos :email para evitar inyecciones SQL
        $sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Devuelve el objeto usuario o false si no existe
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Crear un nuevo usuario (registro)
    public function create($datos) {
        try {
            // La consulta SQL para insertar
            // Asignamos 'cliente' por defecto al rol
            $sql = "INSERT INTO usuario (nombre, email, contraseña, telefono, direccion, rol, fecha_registro)
                    VALUES (:nombre, :email, :password, :telefono, :direccion, 'cliente', NOW())";

            $stmt = $this->db->prepare($sql);

            // Vinculamos los valores del array que recibiremos del controlador
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':password', $datos['password']); // Aquí llegará ya encriptada
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':direccion', $datos['direccion']);

            return $stmt->execute();

        } catch (PDOException $e) {
            // Si el email ya existe (duplicate entry), devolvemos false
            return false;
        }
    }
}
?>