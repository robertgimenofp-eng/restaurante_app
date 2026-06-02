<?php
class Log {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public static function save($id_entidad, $entidad, $accion, $descripcion) {
        require_once 'config/db.php';
        $db = Database::connect();
        
        $id_usuario = $_SESSION['identity']->id_usuario ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $sql = "INSERT INTO log (id_usuario, id_entidad, entidad_afectada, accion, descripcion, ip, fecha_hora) 
                VALUES (:id_u, :id_e, :ent, :acc, :des, :ip, NOW())";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id_u'  => $id_usuario,
            ':id_e'  => $id_entidad,
            ':ent'   => $entidad,
            ':acc'   => $accion,
            ':des'   => $descripcion,
            ':ip'    => $ip
        ]);
    }

    public function getAll() {
        // Unimos con usuarios para saber el nombre de quien hizo la acción
        $sql = "SELECT l.*, u.nombre as nombre_usuario 
                FROM log l 
                LEFT JOIN usuario u ON l.id_usuario = u.id_usuario 
                ORDER BY l.fecha_hora DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}