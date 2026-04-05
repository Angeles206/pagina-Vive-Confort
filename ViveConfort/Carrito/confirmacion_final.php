<?php
// Validamos si el ID existe, si no, ponemos un mensaje genérico
$id_pedido = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : "pendiente";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Gracias por tu compra! - ViveConfort</title>
    <link rel="stylesheet" href="style_confirmacion.css">
</head>
<body>

    <div class="caja-exito">
        <h1>¡Gracias por tu compra!</h1>
        <p>Estamos preparando tus productos con mucho amor.</p>
        
        <div class="numero-pedido">
            Pedido ID: #<?php echo $id_pedido; ?>
        </div>

        <p style="color: #666; font-size: 0.9em;">
            Pronto recibirás un correo con los detalles del envío.
        </p>

        <a href="http://localhost/viveconfort/Productos/productos.php" class="btn-volver">Volver a la tienda</a>
    </div>

</body>
</html>