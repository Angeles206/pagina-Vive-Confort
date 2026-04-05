<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "viveconfort");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    $stmt = $conexion->prepare("SELECT email FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // 1. Generar código de 6 dígitos
        $codigo = rand(100000, 999999);

        // 2. Guardar el código en la BD para verificarlo después
        $update = $conexion->prepare("UPDATE usuarios SET codigo_verificacion = ? WHERE email = ?");
        $update->bind_param("ss", $codigo, $email);
        $update->execute();

        // 3. LÓGICA DE ENVÍO (Simulada)
        // Aquí usarías PHPMailer. Por ahora, para que puedas probar:
        $_SESSION['email_recuperacion'] = $email;
        
        echo "<script>
                alert('Se ha enviado un código a su correo (Código de prueba: $codigo)'); 
                window.location.href='verificar_codigo.php';
              </script>";
    } else {
        echo "<script>alert('El correo no existe'); window.history.back();</script>";
    }
}
?>