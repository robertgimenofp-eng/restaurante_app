<?php

class Database {
    private static $host = "db";
    private static $db = "restaurante_app";
    private static $user = "root";
    private static $password = "root";
    private static $charset = "utf8mb4";

    public static function connect() {
        try {
            $pdo = new PDO(
                "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=" . self::$charset,
                self::$user,
                self::$password
            );

            // Opciones recomendadas
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;

        } catch (PDOException $e) {
            die("Error en la conexión a la base de datos: " . $e->getMessage());
        }
    }
}