<?php
session_start();
include '../inicioS/conexion.php'; 

// Verificamos que tengamos el nombre, el correo y ahora también el tono
if (isset($_GET['nombre']) && isset($_SESSION['correo'])) {
    $nombre = $_GET['nombre'];
    $correo = $_SESSION['correo'];
    // Capturamos el tono. Si por alguna razón no viene, lo dejamos vacío
    $tono = $_GET['tono'] ?? ''; 

    // Añadimos el tono a la condición para ser específicos
    $stmt = $conexion->prepare("DELETE FROM carrito WHERE nombre_producto = ? AND correo_usuario = ? AND tono = ?");
    
    // Ahora pasamos tres "s" (string) y las tres variables
    $stmt->bind_param("sss", $nombre, $correo, $tono);
    
    $stmt->execute();
    $stmt->close();
}

header("Location: carrito.php");
exit;
?>