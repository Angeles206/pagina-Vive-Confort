<?php
session_start();

// 1. Candado de seguridad: Si no ha validado el código, no puede estar aquí
if (!isset($_SESSION['codigo_verificado']) || $_SESSION['codigo_verificado'] !== true) {
    header("Location: restablecer.php");
    exit;
}

$host = "localhost";
$usuario_bd = "root";
$clave_bd = "";
$bd = "viveconfort";

$conexion = new mysqli($host, $usuario_bd, $clave_bd, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nueva_clave = trim($_POST["nueva_clave"] ?? '');
    $confirmar_clave = trim($_POST["confirmar_clave"] ?? '');
    $email = $_SESSION['email_recuperacion'];

    // Validar que las claves coincidan
    if ($nueva_clave === "" || $confirmar_clave === "") {
        echo "<script>alert('Los campos no pueden estar vacíos'); window.history.back();</script>";
    } elseif ($nueva_clave !== $confirmar_clave) {
        echo "<script>alert('Las contraseñas no coinciden'); window.history.back();</script>";
    } else {
        // 2. ACTUALIZAR EN LA BASE DE DATOS
        // Importante: Usamos 'contraseña' con Ñ porque así está en tu tabla
        $update = $conexion->prepare("UPDATE usuarios SET contraseña = ? WHERE email = ?");
        $update->bind_param("ss", $nueva_clave, $email);
        
        if ($update->execute()) {
            session_destroy();
            header("Location: exito_recuperacion.php");
            exit;
        } else {
            echo "<script>alert('Hubo un error al actualizar la base de datos.'); window.history.back();</script>";
        }
        $update->close();
    }
    $conexion->close();
}
?>