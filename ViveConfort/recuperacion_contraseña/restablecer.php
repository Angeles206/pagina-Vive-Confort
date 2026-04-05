<?php
session_start();

// 1. Importar las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ajusta estas rutas según tu estructura de carpetas (PHPMailer-master/src/)
require 'PHPMailer/PHPMailer-master/src/Exception.php';
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "localhost";
    $usuario_bd = "root";
    $clave_bd = "";
    $bd = "viveconfort";

    $conexion = new mysqli($host, $usuario_bd, $clave_bd, $bd);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $email = trim($_POST["correo"] ?? '');

    if ($email === "") {
        $mensaje = "Por favor, ingresa tu correo electrónico.";
        $tipo = "error";
    } else {
        // Verificar si el usuario existe y traer su nombre para el saludo
        $check = $conexion->prepare("SELECT nombres FROM usuarios WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res = $check->get_result();

        if ($usuario = $res->fetch_assoc()) {
            // Generar código de 6 dígitos
            $codigo = rand(100000, 999999);

            // Guardar el código en la base de datos
            $update = $conexion->prepare("UPDATE usuarios SET codigo_verificacion = ? WHERE email = ?");
            $update->bind_param("is", $codigo, $email);
            
            if ($update->execute()) {
                $_SESSION['email_recuperacion'] = $email;

                // --- CONFIGURACIÓN DE ENVÍO CON PHPMAILER ---
                $mail = new PHPMailer(true);

                try {
                    // Configuración del servidor SMTP
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;

                    $mail->Username   = 'maria.dangelesgg@gmail.com'; 

                    $mail->Password   = 'tshsxixqecxvleiv'; 

                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );

                    $mail->setFrom('maria.dangelesgg@gmail.com', 'ViveConfort');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = 'Código de Verificación - ViveConfort';
                    $mail->Body    = "
                        <div style='font-family: sans-serif; border: 1px solid #ad1457; padding: 20px; border-radius: 10px;'>
                            <h2 style='color: #ad1457;'>Hola, " . $usuario['nombres'] . "</h2>
                            <p>Tu código de verificación para restablecer tu contraseña en <b>ViveConfort</b> es:</p>
                            <h1 style='background: #f8f9fa; padding: 10px; text-align: center; letter-spacing: 5px; color: #333;'>$codigo</h1>
                            <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                        </div>";

                    $mail->send();

                    echo "<script>
                        alert('El código de verificación ha sido enviado a tu correo.'); 
                        window.location.href='verificar_codigo.php';
                    </script>";
                    exit;

                } catch (Exception $e) {
                    $mensaje = "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
                    $tipo = "error";
                }
            } else {
                $mensaje = "Error al generar el código de seguridad.";
                $tipo = "error";
            }
        } else {
            $mensaje = "El correo ingresado no está registrado.";
            $tipo = "error";
        }
        $check->close();
    }
    $conexion->close();
}
?>

<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>ViveConfort - Recuperar Acceso</title> 
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.Sesion.css"> 
</head>

    <header class="header-tienda">
        <div class="logo">
            <a href="../Productos/productos.php">
                <img src="Imagenes/ViveConfort.png" class="logo-img" alt="Logo ViveConfort">
            </a>
        </div>
    </header>

<body>
<div id="contenedor">
    <?php if (isset($mensaje)): ?>
        <div class="alerta <?php echo $tipo; ?>" style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; border: 1px solid;">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <h1 class="titulo-login" style="font-family: 'Times New Roman', Times, serif; font-style: italic;">Recuperar Contraseña</h1>
    <p style="text-align: center; margin-bottom: 20px;">Ingresa tu correo para enviarte un código de verificación.</p>

    <form method="POST" action="">
        <div class="controles">
            <div class="completo">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" required placeholder="ejemplo@correo.com">
            </div>
        </div>

        <div class="botones-container">
            <button type="submit" class="btn-ingresar">Enviar Código</button>
            <div class="fila-secundaria" style="margin-top: 15px; text-align: center;">
                <a href="../inicioS/login.php" class="btn-secundario" style="text-decoration: none; color: #ad1457;">Volver al Login</a>
            </div>
        </div>
    </form>
</div>
</body>
</html>