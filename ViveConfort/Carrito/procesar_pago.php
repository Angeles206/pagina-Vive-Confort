<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "viveconfort");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $metodo = $_POST['pago'];
    $total = 71000; // Esto debería venir de tu variable de carrito
    $email_cliente = $_SESSION['email']; // Suponiendo que inició sesión

    // 1. Guardar la orden en la tabla 'pedidos'
    $stmt = $conexion->prepare("INSERT INTO pedidos (email_cliente, total, metodo_pago, estado) VALUES (?, ?, ?, 'Pendiente')");
    $stmt->bind_param("sds", $email_cliente, $total, $metodo);
    
    if ($update->execute()) {
        $id_pedido = $conexion->insert_id;

        if ($metodo === "entrega") {
            // Si es contra entrega, lo mandamos directo a la página de éxito
            header("Location: confirmacion_final.php?id=$id_pedido");
        } else {
            // AQUÍ LLAMARÍAMOS A LA API (Mercado Pago / Wompi)
            // Por ahora, te mostraré cómo crear la página de confirmación básica
            header("Location: confirmacion_final.php?id=$id_pedido");
        }
    }
}
?>