<?php
$host = "localhost";
$user = "root";
$pass = ""; // En XAMPP por defecto está vacío
$db = "viveconfort"; // El nombre exacto de tu base de datos en phpMyAdmin

$conexion = new mysqli($host, $user, $pass, $db);
$conexion->set_charset("utf8mb4");

// Verificamos si hubo un error para que no te rompas la cabeza adivinando
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Configuramos para que reconozca tildes y la "ñ"
$conexion->set_charset("utf8");
?>