<?php
session_start();
include 'conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['correo'])) {
    echo json_encode(['status' => 'error', 'message' => 'Inicia sesión primero']);
    exit;
}

$email = $_SESSION['correo'];
$nombre = $_POST['nombre'] ?? '';

if (!empty($nombre)) {
    // Verificamos usando nombre_producto ya que es tu columna real
    $check = $conexion->prepare("SELECT nombre_producto FROM favoritos WHERE email = ? AND nombre_producto = ?");
    $check->bind_param("ss", $email, $nombre);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        echo json_encode(['status' => 'exists', 'message' => 'Ya está en favoritos']);
    } else {
        $ins = $conexion->prepare("INSERT INTO favoritos (email, nombre_producto) VALUES (?, ?)");
        $ins->bind_param("ss", $email, $nombre);
        if ($ins->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conexion->error]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nombre de producto vacío']);
}
?>