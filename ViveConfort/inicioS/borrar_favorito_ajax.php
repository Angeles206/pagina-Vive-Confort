<?php
session_start();
include 'conexion.php';
header('Content-Type: application/json');

$email = $_SESSION['correo'] ?? '';
$nombre = $_POST['nombre'] ?? '';

if (!empty($nombre) && !empty($email)) {
    $stmt = $conexion->prepare("DELETE FROM favoritos WHERE email = ? AND nombre_producto = ?");
    $stmt->bind_param("ss", $email, $nombre);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conexion->error]);
    }
}
?>