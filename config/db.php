<?php
class Database {
    // Si existe la variable de entorno (Nube), úsala. Si no, usa "db" (Docker local)
    private static $host;
    private static $db;
    private static $user;
    private static $password;

    public static function connect() {
    // 1. Obtenemos los valores o usamos los de local
    $host = getenv('MYSQLHOST') ?: 'db'; 
    $db   = getenv('MYSQLDATABASE') ?: 'restaurante_app';
    $user = getenv('MYSQLUSER') ?: 'root';
    $pass = getenv('MYSQLPASSWORD') ?: 'root';
    $port = getenv('MYSQLPORT') ?: '3306'; // <--- AÑADE ESTO

    try {
        // Añadimos el puerto a la cadena de conexión (host=...;port=...)
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
        $conexion = new PDO($dsn, $user, $pass);
        
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch (PDOException $e) {
        // Esto te dirá exactamente qué falla si sigue sin conectar
        die("Error de conexión: " . $e->getMessage());
    }
}
}