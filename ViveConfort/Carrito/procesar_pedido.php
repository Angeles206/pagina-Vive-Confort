<?php
session_start();
include '../inicioS/conexion.php';

// 1. LLAMADO MANUAL A PHPMAILER (Rutas verificadas según tus capturas de pantalla)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../recuperacion_contraseña/PHPMailer/PHPMailer-master/src/Exception.php';
require '../recuperacion_contraseña/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require '../recuperacion_contraseña/PHPMailer/PHPMailer-master/src/SMTP.php';

// 2. ASEGURAR EL CORREO DEL USUARIO
// Buscamos en todas las posibles variables de sesión que podrías haber usado
$email = $_SESSION['correo'] ?? $_SESSION['email'] ?? $_SESSION['email_usuario'] ?? null;
$metodo = $_GET['metodo'] ?? 'entrega'; 

if (!$email) { 
    // Si no hay correo, detenemos y avisamos para que no falle el PHPMailer
    die("Error: No se encontró el correo del usuario en la sesión. Por favor, inicia sesión de nuevo."); 
}

// 3. CALCULAR TOTAL DEL CARRITO
$query_total = "SELECT SUM(p.precio * c.cantidad) as total 
                FROM carrito c 
                JOIN productos p ON c.nombre_producto = p.nombre_producto 
                WHERE c.correo_usuario = ?";
$stmt_t = $conexion->prepare($query_total);
$stmt_t->bind_param("s", $email);
$stmt_t->execute();
$res_t = $stmt_t->get_result()->fetch_assoc();
$total_final = $res_t['total'] ?? 0;

// 4. INSERTAR PEDIDO EN LA BASE DE DATOS (4 columnas, 4 tipos 'sdss')
$stmt_i = $conexion->prepare("INSERT INTO pedidos (email_cliente, total, metodo_pago, estado) VALUES (?, ?, ?, ?)");
$estado_inicial = ($metodo === 'entrega') ? 'Pendiente' : 'Procesando';
$stmt_i->bind_param("sdss", $email, $total_final, $metodo, $estado_inicial);

if ($stmt_i->execute()) {
    $id_pedido = $conexion->insert_id; // Obtenemos el ID del pedido recién creado

    // 5. ENVIAR CORREO ELECTRÓNICO
    $mail = new PHPMailer(true);

    try {
        // ... (tus configuraciones de Host, Username, Password, etc.)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'maria.dangelesgg@gmail.com'; 
        $mail->Password   = 'tshsxixqecxvleiv'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // AGREGA ESTO AQUÍ: Esto soluciona el error de la captura de pantalla
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
        $mail->Subject = 'Confirmacion de Compra - ViveConfort #' . $id_pedido;
        $mail->CharSet = 'UTF-8';
        
        $mail->Body = "
            <div style='border: 2px solid #ad1457; padding: 20px; border-radius: 10px; font-family: sans-serif; max-width: 500px;'>
                <h2 style='color: #ad1457; text-align: center;'>¡Gracias por tu compra en ViveConfort!</h2>
                <p>Hola, hemos recibido tu pedido con éxito. Aquí tienes el resumen:</p>
                <hr style='border: 1px solid #fce4ec;'>
                <p><strong>Número de Pedido:</strong> #$id_pedido</p>
                <p><strong>Total a pagar:</strong> $" . number_format($total_final, 0, ',', '.') . " COP</p>
                <p><strong>Método de pago:</strong> " . ($metodo == 'entrega' ? 'Pago contra entrega' : 'Pago Digital') . "</p>
                <hr style='border: 1px solid #fce4ec;'>
                <p style='text-align: center; color: #ad1457;'><b>Pronto recibirás tus productos en tu dirección registrada.</b></p>
            </div>";

        $mail->send();
        
        header("Location: confirmacion_final.php?id=" . $id_pedido);
        exit;

    } catch (Exception $e) {
        header("Location: confirmacion_final.php?id=" . $id_pedido);
    }
} else {
    echo "Error al guardar el pedido: " . $conexion->error;
}
?>