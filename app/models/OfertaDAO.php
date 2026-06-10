<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/Oferta.php';

class OfertaDAO {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    public function buscarPorCodigo($codigo) {
        $sql = "SELECT * FROM oferta 
                WHERE codigo_opcional = ? 
                AND fecha_inicio <= CURDATE() 
                AND fecha_fin >= CURDATE()";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$codigo]);
        return $stmt->fetchObject('Oferta'); 
    }

    public function getOfertaActivaAssoc() {
        $hoy = date('Y-m-d');
        $sql = "SELECT * FROM oferta 
                WHERE fecha_inicio <= :hoy 
                AND fecha_fin >= :hoy 
                ORDER BY id_oferta DESC LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':hoy', $hoy);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Oferta');
        return $stmt->fetch();
    }
}
?>