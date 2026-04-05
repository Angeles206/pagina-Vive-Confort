<?php
session_start();

if (!isset($_SESSION['email_recuperacion'])) {
    header("Location: restablecer.php");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "viveconfort");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_SESSION['email_recuperacion'];
    $codigo_ingresado = trim($_POST['codigo_ingresado'] ?? '');

    // IMPORTANTE: Verifica que la columna se llame 'codigo_verificacion'
    $stmt = $conexion->prepare("SELECT codigo_verificacion FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($user = $resultado->fetch_assoc()) {
        // Comparamos el código (usamos == por si uno es texto y otro número)
        if (!empty($codigo_ingresado) && $user['codigo_verificacion'] == $codigo_ingresado) {
            
            // Si es correcto, damos permiso para el último paso
            $_SESSION['codigo_verificado'] = true;
            
            // Redirigimos al archivo final (cámbiale el nombre si el tuyo es distinto)
            header("Location: cambiar_clave.php"); 
            exit;
        } else {
            echo "<script>alert('Código incorrecto. Verifica el número en tu correo.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Error de sesión. Intenta de nuevo.'); window.location.href='restablecer.php';</script>";
    }
}
?>