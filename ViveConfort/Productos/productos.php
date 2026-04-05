<?php
$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "viveconfort";
$conexion = new mysqli($host, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql_cat = "SELECT DISTINCT categoria FROM productos ORDER BY categoria ASC";
$res_cat = $conexion->query($sql_cat);

$sql = "SELECT * FROM productos ORDER BY nombre_producto ASC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Productos - ViveConfort</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style_produc.css">
</head>
<body>

<header class="header-tienda">
    <div class="logo">
        <a href="http://localhost/viveconfort/Inicio/Inicio.PHP">
            <img src="Imagenes/ViveConfort.png" class="logo-img" alt="ViveConfort Logo">
        </a>
    </div>

    <div class="header-derecha">
        <input type="text" id="inputBusqueda" class="busqueda-real" placeholder="🔍 Buscar productos...">
        <div class="iconos-header">
            <a href="http://localhost/viveconfort/inicioS/favoritos.php" class="btn-favorito-emoji">❤</a>
            <a href="http://localhost/viveconfort/Carrito/carrito.php" class="btn-carrito-emoji">🛒</a>
            <a href="http://localhost/viveconfort/inicioS/perfil.php" class="icon-link">
                <img src="Imagenes/persona.png" alt="Cuenta" class="header-icon-img">
            </a>    
        </div>
    </div>
</header>

<hr class="linea-separadora">

<h1 class="titulo-pagina-productos">Productos</h1>

<div class="main-content">
    <aside class="sidebar-filtros">
        <h3>Categorías</h3>
        <ul class="lista-categorias">
            <li onclick="filtrarCategoria('todas')">✨ Ver todo</li>
            <?php
            if ($res_cat && $res_cat->num_rows > 0) {
                while($cat = $res_cat->fetch_assoc()) {
                    echo "<li onclick=\"filtrarCategoria('".htmlspecialchars($cat['categoria'])."')\">🌸 ".htmlspecialchars($cat['categoria'])."</li>";
                }
            }
            ?>
        </ul>
    </aside>

    <div class="grid-productos" id="contenedorProductos">
        <?php
        if ($resultado && $resultado->num_rows > 0) {
            while($row = $resultado->fetch_assoc()) {
                $imagenSrc = !empty($row['imagen']) ? 'data:image/jpeg;base64,'.base64_encode($row['imagen']) : "img/default.png";
                $urlDetalle = "infoP.php?nombre=". urlencode($row['nombre_producto']);
                ?>
                <div class="tarjeta-producto" 
                     onclick="window.location.href='<?php echo $urlDetalle; ?>'"
                     data-nombre="<?php echo strtolower($row['nombre_producto']); ?>" 
                     data-categoria="<?php echo strtolower($row['categoria']); ?>">
                    
                    <div class="contenedor-img">
                        <img src="<?php echo $imagenSrc; ?>" alt="Producto">
                    </div>
                    
                    <div class="info-producto">
                        <div class="nombre-marca">
                            <span class="nombre-txt"><?php echo htmlspecialchars($row['nombre_producto']); ?></span><br>
                            <small class="marca-txt"><?php echo htmlspecialchars($row['marca']); ?></small>
                        </div>
                        <div class="precio-prod-estilo">
                            $<?php echo number_format($row['precio'], 0, ',', '.'); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>

<script>
function filtrarCategoria(cat) {
    let productos = document.querySelectorAll('.tarjeta-producto');
    let buscado = cat.toLowerCase();
    productos.forEach(prod => {
        let catProd = prod.getAttribute('data-categoria');
        // Usamos 'grid' en lugar de 'flex' para mantener la estructura original
        prod.style.display = (buscado === 'todas' || catProd === buscado) ? "grid" : "none";
    });
}

document.getElementById('inputBusqueda').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let productos = document.querySelectorAll('.tarjeta-producto');
    productos.forEach(prod => {
        let nombre = prod.getAttribute('data-nombre');
        prod.style.display = nombre.includes(filtro) ? "grid" : "none";
    });
});
</script>
</body>
</html>