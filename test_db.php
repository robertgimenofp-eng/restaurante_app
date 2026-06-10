<?php
require_once __DIR__ . '/database/db.php';

try {
    $db = Database::connect();
    echo "✅ Conexión exitosa a la base de datos.";
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
