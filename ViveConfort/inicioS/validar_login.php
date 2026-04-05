<?php
session_start();

// 1. Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "viveconfort");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// 2. Recibir datos del formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email_ingresado = trim($_POST['email'] ?? '');
    $password_ingresada = trim($_POST['pass'] ?? ''); 

    if (empty($email_ingresado) || empty($password_ingresada)) {
        echo "<script>alert('Por favor llena todos los campos'); window.location.href='login.php';</script>";
        exit;
    }

    // 3. Buscar al usuario (usando 'contraseña' con ñ)
    $stmt = $conexion->prepare("SELECT email, contraseña, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email_ingresado);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($user = $resultado->fetch_assoc()) {
        
        // 4. COMPARACIÓN EN TEXTO PLANO
        if ($password_ingresada === $user['contraseña']) {
            
            // Éxito: Guardamos los datos necesarios en la sesión
            $_SESSION['correo'] = $user['email'];
            $_SESSION['rol']    = $user['rol'];

            // REDIRECCIÓN A PRODUCTOS
            // Ajusta la ruta si tu archivo de productos está en otra carpeta
            header("Location: ../Productos/productos.php");
            exit;

        } else {
            echo "<script>alert('La contraseña es incorrecta'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('El correo no está registrado'); window.location.href='login.php';</script>";
    }
    $stmt->close();
}
$conexion->close();
?>