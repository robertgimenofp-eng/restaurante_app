<?php
require 'database/db.php';
$database = new Database();
$db = $database->connect();
$stmt = $db->query('SELECT * FROM oferta');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
