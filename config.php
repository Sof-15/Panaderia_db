<?php
// Configuración de conexión a MySQL
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'panaderia_db';
$port = 3306;

// Crear conexión
$conn = new mysqli($host, $user, $password, $database, $port);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]));
}

// Establecer charset a UTF-8
$conn->set_charset("utf8");

?>
