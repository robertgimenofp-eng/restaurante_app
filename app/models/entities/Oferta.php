<?php
// models/Oferta.php
require_once __DIR__ . '/../config/db.php'; 

class Oferta {
    
    public static function buscarPorCodigo($codigo) {
        
        // 1. Conexión (Esto te devuelve un objeto PDO)
        $db = Database::connect(); 
        
        // 2. Consulta SQL (Igual que antes)
        $sql = "SELECT * FROM oferta 
                WHERE codigo_opcional = ? 
                AND fecha_inicio <= CURDATE() 
                AND fecha_fin >= CURDATE()";
        
        // 3. Preparar la consulta
        $stmt = $db->prepare($sql);
        
        // 4. EJECUTAR (Aquí es donde cambia la sintaxis para PDO)
        // En PDO pasamos los datos dentro de execute en un array
        $stmt->execute([$codigo]);
        
        // 5. OBTENER EL RESULTADO
        // PDO::FETCH_OBJ nos devuelve un objeto anónimo (igual que fetch_object en mysqli)
        return $stmt->fetch(PDO::FETCH_OBJ); 
    }
}
?>