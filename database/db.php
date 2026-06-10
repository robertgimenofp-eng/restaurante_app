<?php

class Database
{
    // private static $host = "localhost";
    // private static $db = "restaurante_app";
    // private static $user = "root";
    // private static $password = "";
    // private static $port = 3306;
    // private static $charset = "utf8mb4";

    //====USO DOCKER====
    private static $host = "127.0.0.1";
    private static $user = "root";
    private static $password = "root";
    private static $db = "restaurante_app";
    private static $port = 3307;
    private static $charset = "utf8mb4";

    public static function connect()
    {
        try {
            $pdo = new PDO(
                "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$db . ";charset=" . self::$charset,
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