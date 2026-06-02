<?php
require_once __DIR__ . '/../config/db.php';

class ProductoDAO {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
 
    public function getAll() {
        $query = "SELECT * FROM producto";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById($id) {
        $query = "SELECT * FROM producto WHERE id_producto = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getByIdAssoc($id) {
        $query = "SELECT * FROM producto WHERE id_producto = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nombre, $descripcion, $precio, $stock, $categoria, $img_final) {
        $sql = "INSERT INTO producto (nombre, descripcion, precio, stock, categoria, imagen_url) 
                VALUES (:nombre, :descripcion, :precio, :stock, :categoria, :imagen)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':imagen', $img_final);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':categoria', $categoria);

        if ($stmt->execute()) {
            return $this->db->lastInsertId(); // devuelve el ID creado
        }
        return false;
    }

    public function update($id, $nombre, $descripcion, $precio, $stock, $categoria, $nombre_fichero = null) {
        $sql = "UPDATE producto SET nombre=:nombre, descripcion=:descripcion, precio=:precio, stock=:stock, categoria=:categoria";
        if ($nombre_fichero) {
            $sql .= ", imagen_url=:imagen";
        }
        $sql .= " WHERE id_producto=:id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($nombre_fichero) {
            $stmt->bindParam(':imagen', $nombre_fichero);
        }

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':categoria', $categoria);

        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM producto WHERE id_producto = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getImagenesByIds($ids_array) {
        $imagenes_map = [];
        if(!empty($ids_array)) {
            $ids_str = implode(',', array_unique($ids_array));
            // Aseguramos que solo pasen enteros para evitar inyección SQL en IN()
            $ids_clean = implode(',', array_map('intval', explode(',', $ids_str)));
            $sql = "SELECT id_producto, imagen_url as imagen FROM producto WHERE id_producto IN ($ids_clean)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $imagenes_map[$row['id_producto']] = $row['imagen'];
            }
        }
        return $imagenes_map;
    }
}
?>