<?php
session_start();
include '../inicioS/conexion.php';

if (!isset($_SESSION['correo'])) {
    die(json_encode(['status' => 'error', 'message' => 'Inicia sesión primero']));
}

$email = $_SESSION['correo'];
$nombre = $_POST['nombre'] ?? null;
$cantidad = $_POST['cantidad'] ?? 1;
$tono = $_POST['tono'] ?? 'N/A'; // Usamos N/A si no viene un tono

if ($nombre) {
    // IMPORTANTE: Buscamos por nombre Y por tono para no mezclar productos
    $check = $conexion->prepare("SELECT cantidad FROM carrito WHERE nombre_producto = ? AND correo_usuario = ? AND tono = ?");
    $check->bind_param("sss", $nombre, $email, $tono);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        // Si ya existe ESE producto con ESE tono, sumamos la cantidad
        $stmt = $conexion->prepare("UPDATE carrito SET cantidad = cantidad + ? WHERE nombre_producto = ? AND correo_usuario = ? AND tono = ?");
        $stmt->bind_param("isss", $cantidad, $nombre, $email, $tono);
    } else {
        // Si es un tono nuevo o producto nuevo, lo insertamos
        $stmt = $conexion->prepare("INSERT INTO carrito (correo_usuario, nombre_producto, tono, cantidad) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $email, $nombre, $tono, $cantidad);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conexion->error]);
    }
}
?>