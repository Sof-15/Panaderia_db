<?php
$conexion = mysqli_connect("localhost", "root", "", "panaderia_db");

if (!$conexion) {
    echo "Fallo la conexión";
} else {
    error_log("Conexión exitosa a la panadería");
}
?>