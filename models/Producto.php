<?php
// models/Producto.php

class Producto {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        // Pedimos todos los productos
        $query = "SELECT * FROM producto";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // En models/Producto.php
    public function getById($id) {
        $query = "SELECT * FROM producto WHERE id_producto = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
?>