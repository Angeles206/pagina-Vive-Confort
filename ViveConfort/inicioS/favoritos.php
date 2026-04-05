<?php
session_start();
include 'conexion.php'; 

$sesion_activa = isset($_SESSION['correo']);

if ($sesion_activa) {
    $email_usuario = $_SESSION['correo'];
    
    // Consulta para traer los favoritos
    $query = "SELECT f.nombre_producto, p.precio, p.imagen 
              FROM favoritos f 
              JOIN productos p ON f.nombre_producto = p.nombre_producto 
              WHERE f.email = ?";

    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $email_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViveConfort - Mis Favoritos</title>
    <link rel="stylesheet" href="style_favoritos.css">
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

    <div id="contenedor-favoritos">
        <main class="contenedor-principal">
            
            <?php if (!$sesion_activa): ?>
                <div class="pantalla-bloqueo-total">
                    <div class="cuadro-bloqueo">
                        <h2 class="titulo-bloqueo">Mis Favoritos</h2>
                        <p class="texto-bloqueo">Por favor, inicia sesión para ver tus favoritos.</p>
                        <a href="login.php" class="btn-login-bloqueo">Iniciar Sesión</a>
                    </div>
                </div>

            <?php else: ?>
            <h1 class="titulo-pagina">Tus favoritos</h1>

                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <div class="cuadricula-favoritos">
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            
                            <div class="tarjeta-favorito">
                                <a href="../Productos/infoP.php?nombre=<?php echo urlencode($row['nombre_producto']); ?>" class="enlace-detalle">                                    
                                    <div class="marco-foto">
                                        <?php if(!empty($row['imagen'])): ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagen']); ?>" alt="Producto">
                                        <?php else: ?>
                                            <img src="Imagenes/default.png" alt="Sin imagen">
                                        <?php endif; ?>
                                    </div>
                                    <p class="nombre-prod"><?php echo htmlspecialchars($row['nombre_producto']); ?></p>
                                </a>
                                <p class="precio-prod">$<?php echo number_format($row['precio'] ?? 0, 0, ',', '.'); ?></p>
                                
                                <a href="borrar_favorito.php?nombre=<?php echo urlencode($row['nombre_producto']); ?>&v=<?php echo time(); ?>" 
                                   class="quitar-favorito" 
                                   title="Quitar de favoritos">❤</a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="mensaje-vacio">
                        <p>Aún no tienes productos favoritos.</p>
                    </div>
                <?php endif; ?>

                <div class="footer-perfil">
                    <a href="../Productos/productos.php" class="btn-volver">Volver a la tienda</a>
                    <a href="perfil.php" class="btn-perfil">Volver al perfil</a>
                </div>
            <?php endif; ?>

        </main>
    </div>

</body>
</html>