<?php
class Database {
    // Si existe la variable de entorno (Nube), úsala. Si no, usa "db" (Docker local)
    private static $host;
    private static $db;
    private static $user;
    private static $password;

    public static function connect() {
        // Asignamos valores dinámicamente
        self::$host = getenv('MYSQLHOST') ?: 'db'; 
        self::$db   = getenv('MYSQLDATABASE') ?: 'restaurante_app';
        self::$user = getenv('MYSQLUSER') ?: 'root';
        self::$password = getenv('MYSQLPASSWORD') ?: 'root';
        
        // En Railway a veces el puerto es distinto, pero por defecto suele ir bien.
        // Si falla, avísame.

        try {
            $conexion = new PDO("mysql:host=".self::$host.";dbname=".self::$db.";charset=utf8mb4", self::$user, self::$password);
            // ... resto de tu código igual ...
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}