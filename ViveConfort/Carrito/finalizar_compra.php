<?php
session_start();
include '../inicioS/conexion.php';

// Verificación de sesión única y clara
$email_usuario = $_SESSION['correo'] ?? $_SESSION['email'] ?? null;
if (!$email_usuario) { 
    header("Location: ../inicioS/Login.php"); 
    exit; 
}

// 1. Obtener datos del usuario (Uso de prepared statements para seguridad)
$query_u = "SELECT nombres, ciudad, direccion, telefono FROM usuarios WHERE email = ?";
$stmt_u = $conexion->prepare($query_u);
$stmt_u->bind_param("s", $email_usuario);
$stmt_u->execute();
$user_data = $stmt_u->get_result()->fetch_assoc();

// 2. Obtener productos y calcular total directamente desde la DB
$query_c = "SELECT c.nombre_producto, c.cantidad, p.precio 
            FROM carrito c 
            JOIN productos p ON c.nombre_producto = p.nombre_producto 
            WHERE c.correo_usuario = ?";
$stmt_c = $conexion->prepare($query_c);
$stmt_c->bind_param("s", $email_usuario);
$stmt_c->execute();
$res_c = $stmt_c->get_result();

$total = 0;
$items = [];
while($row = $res_c->fetch_assoc()){
    $total += ($row['precio'] * $row['cantidad']);
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - ViveConfort</title>
    <link rel="stylesheet" href="style_finalizar.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <script type="text/javascript" src="https://checkout.epayco.co/checkout.js"></script>
</head>
<body>

<div id="contenedor-factura">
    <h1 class="titulo-pagina">Finalizar compra</h1>

    <div class="resumen-caja">
        <h3>Resumen de la compra:</h3>
        <table class="tabla-resumen">
            <thead>
                <tr>
                    <th>Productos</th>
                    <th style="text-align: center;">Cantidad</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($items) > 0): ?>
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre_producto']); ?></td>
                        <td style="text-align: center;"><?php echo $item['cantidad']; ?></td>
                        <td>$<?php echo number_format($item['precio'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">El carrito está vacío</td></tr>
                <?php endif; ?>
                
                <tr class="fila-total">
                    <td colspan="2">Total</td>
                    <td>$<?php echo number_format($total, 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-envio-pago">
        <div class="metodo-pago">
            <h3>Método de pago:</h3>
            <label>
                <input type="radio" name="pago" value="digital"> Tarjeta / PSE / Nequi
            </label><br>
            <label>
                <input type="radio" name="pago" value="entrega" checked> Pago contra entrega
            </label>
        </div>

        <div class="divisor-vertical"></div>

        <div class="datos-usuario">
            <div class="dato-bloque"><strong>Ciudad:</strong> <span><?php echo htmlspecialchars($user_data['ciudad'] ?? 'No definida'); ?></span></div>
            <div class="dato-bloque"><strong>Dirección:</strong> <span><?php echo htmlspecialchars($user_data['direccion'] ?? 'No definida'); ?></span></div>
            <div class="dato-bloque"><strong>Número:</strong> <span><?php echo htmlspecialchars($user_data['telefono'] ?? 'No definido'); ?></span></div>
            <a href="../inicioS/perfil.php" class="btn-editar">Editar información</a>
        </div>
    </div>

    <div class="acciones-finales">
        <a href="carrito.php" class="btn-regresar">Regresar</a>
        <button type="button" id="btn-confirmar" onclick="procesarPago()" class="btn-confirmar">Confirmar compra</button>
    </div>
</div>

<script>
function procesarPago() {
    // Bloquear el botón para evitar doble clic
    const btn = document.getElementById('btn-confirmar');
    btn.disabled = true;
    btn.innerText = "Procesando...";

    const metodoInput = document.querySelector('input[name="pago"]:checked');
    
    if (!metodoInput) {
        alert("Por favor selecciona un método de pago.");
        btn.disabled = false;
        btn.innerText = "Confirmar compra";
        return;
    }

    const metodo = metodoInput.value;

    if (metodo === 'entrega') {
        // Redirigir a procesar_pedido con el parámetro de entrega
        window.location.href = "procesar_pedido.php?metodo=entrega";
    } else {
        // Configuración de ePayco
        var handler = ePayco.checkout.configure({
            key: 'acdaf87ab7444c7f01a030ce9a4c170d', 
            test: true
        });

        var data = {
            name: "Compra ViveConfort",
            description: "Productos de belleza",
            currency: "cop",
            amount: "<?php echo $total; ?>",
            tax_base: "0",
            tax: "0",
            country: "co",
            lang: "es",
            external: "false",
            confirmation: "http://localhost/viveconfort/Carrito/procesar_pedido.php",
            response: "http://localhost/viveconfort/Carrito/confirmacion_final.php",
            email_billing: "<?php echo $email_usuario; ?>",
            name_billing: "<?php echo $user_data['nombres']; ?>",
            address_billing: "<?php echo $user_data['direccion']; ?>",
            mobile_billing: "<?php echo $user_data['telefono']; ?>"
        };

        handler.open(data);
        
        // Si cierran el widget, rehabilitar el botón
        setTimeout(() => {
            btn.disabled = false;
            btn.innerText = "Confirmar compra";
        }, 3000);
    }
}
</script>
</body>
</html>