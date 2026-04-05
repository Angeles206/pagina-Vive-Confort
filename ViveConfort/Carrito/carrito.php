<?php
session_start();
include '../inicioS/conexion.php'; 

// Verificamos si hay sesión activa
$email_usuario = $_SESSION['correo'] ?? $_SESSION['email'] ?? null;
$sesion_activa = ($email_usuario !== null);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito - ViveConfort</title>
    <link rel="stylesheet" href="style_carrito.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header-tienda">
        <div class="logo">
            <a href="../Productos/productos.php">
                <img src="Imagenes/ViveConfort.png" class="logo-img" alt="Logo ViveConfort">
            </a>
        </div>
    </header>

    <div id="contenedor-carrito">
        <main class="contenedor-principal">

            <?php if (!$sesion_activa): ?>
                <div class="pantalla-bloqueo-total">
                    <div class="cuadro-bloqueo">
                        <h2 class="titulo-bloqueo">Tu Carrito</h2>
                        <p class="texto-bloqueo">Por favor, inicia sesión para ver y gestionar tu carrito de compras.</p>
                        <a href="../inicioS/Login.php" class="btn-login-bloqueo">Iniciar Sesión</a>
                        <div class="footer-bloqueo">
                            <a href="../Productos/productos.php" class="link-volver-suave">Volver a la tienda</a>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <h1 class="titulo-pagina">Tu Carrito</h1>

                <?php
                // Añadimos c.tonalidad a la consulta para diferenciar productos por color
                $query = "SELECT c.nombre_producto, c.cantidad, c.tono, p.precio, p.imagen 
                        FROM carrito c 
                        JOIN productos p ON c.nombre_producto = p.nombre_producto 
                        WHERE c.correo_usuario = ?";
                
                $stmt = $conexion->prepare($query);
                $stmt->bind_param("s", $email_usuario);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $total_general = 0;

                if ($resultado->num_rows > 0): ?>
                    <table class="tabla-carrito">
                        <thead>
                            <tr>
                                <th>Vista</th>
                                <th>Producto</th>
                                <th>Tono</th> <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($fila = $resultado->fetch_assoc()): 
                                $subtotal = $fila['precio'] * $fila['cantidad'];
                                $total_general += $subtotal;
                                $src = !empty($fila['imagen']) ? 'data:image/jpeg;base64,'.base64_encode($fila['imagen']) : "img/default.png";
        
        // URL para el detalle del producto
                                $url_detalle = "../Productos/infoP.php?nombre=" . urlencode($fila['nombre_producto']);
                            ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $url_detalle; ?>">
                                            <div class="cuadro-foto-carrito">
                                                <img src="<?php echo $src; ?>" alt="Producto">
                                            </div>
                                        </a>
                                    </td>
                                    <td class="nombre-celda">
                                        <a href="<?php echo $url_detalle; ?>" class="link-producto">
                                            <?php echo htmlspecialchars($fila['nombre_producto']); ?>
                                        </a>
                                    </td>
                                    <td class="tono-celda">
                                        <?php echo !empty($fila['tono']) ? htmlspecialchars($fila['tono']) : 'N/A'; ?>
                                    </td>
                                    <td><?php echo $fila['cantidad']; ?></td>
                                    <td>$<?php echo number_format($fila['precio'], 0, ',', '.'); ?></td>
                                    <td>$<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                    <td>
                                        <a href="borrar_producto.php?nombre=<?php echo urlencode($fila['nombre_producto']); ?>&tono=<?php echo urlencode($fila['tono'] ?? ''); ?>" class="btn-borrar" title="Eliminar">🗑</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <div class="seccion-total">
                        <span>Total a pagar:</span>
                        <span class="total-monto">$<?php echo number_format($total_general, 0, ',', '.'); ?></span>
                    </div>

                    <div class="contenedor-botones-final">
                        <a href="finalizar_compra.php" class="btn-confirmar">Finalizar Compra</a>
                    </div>

                <?php else: ?>
                    <div class="mensaje-vacio">
                        <p>El carrito está vacío.</p>
                    </div>
                <?php endif; ?>

                <div class="footer-perfil">
                    <a href="../Productos/productos.php" class="btn-volver">Volver a la tienda</a>
                    <a href="../inicioS/perfil.php" class="btn-perfil">Volver al perfil</a>
                </div>
                
            <?php endif; ?>

        </main>
    </div>

</body>
</html>