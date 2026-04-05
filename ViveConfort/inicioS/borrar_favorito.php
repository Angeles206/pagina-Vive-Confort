<?php
session_start();
include 'conexion.php'; 

// 1. Capturamos los datos
$nombre = $_GET['nombre'] ?? null;
$email = $_SESSION['correo'] ?? null;

// 2. Verificamos que ambos existan antes de intentar borrar
if ($nombre && $email) {
    // 3. El DELETE debe ser exacto. 
    // Usamos 'email' porque así se llama tu columna en la tabla favoritos.
    $stmt = $conexion->prepare("DELETE FROM favoritos WHERE nombre_producto = ? AND email = ?");
    
    if ($stmt) {
        $stmt->bind_param("ss", $nombre, $email);
        $stmt->execute();
        $stmt->close();
    }
}

// 4. Redirigimos siempre a favoritos para refrescar la lista
header("Location: favoritos.php");
exit;