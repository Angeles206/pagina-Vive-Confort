<?php
session_start();
// 1. Conexión a la base de datos de ViveConfort
$conexion = new mysqli("localhost", "root", "", "viveconfort");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// 2. Obtener producto por nombre desde la URL
$nombre_producto = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$sql = "SELECT * FROM productos WHERE nombre_producto = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $nombre_producto);
$stmt->execute();
$resultado = $stmt->get_result();
$row = $resultado->fetch_assoc();

if (!$row) { 
    die("Producto no encontrado."); 
}

// 3. Procesar imágenes desde BLOB
$imagen1 = !empty($row['imagen']) ? 'data:image/jpeg;base64,'.base64_encode($row['imagen']) : "img/default.png";
$imagen2 = !empty($row['imagen2']) ? 'data:image/jpeg;base64,'.base64_encode($row['imagen2']) : null;

// 4. Procesar tonalidades
$lista_tonos = [];
if (!empty($row['tonalidades'])) {
    $lista_tonos = explode(',', $row['tonalidades']);
}

// 5. Verificar si ya es favorito para pintar el corazón al cargar
$es_favorito = false;
if (isset($_SESSION['correo'])) {
    $check_fav = $conexion->prepare("SELECT nombre_producto FROM favoritos WHERE email = ? AND nombre_producto = ?");
    $check_fav->bind_param("ss", $_SESSION['correo'], $row['nombre_producto']);
    $check_fav->execute();
    if ($check_fav->get_result()->num_rows > 0) { $es_favorito = true; }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title><?php echo htmlspecialchars($row['nombre_producto']); ?> - ViveConfort</title>
    <link rel="stylesheet" href="style_detalle.css">
</head>
<body>

<header class="header-tienda">
    <div class="logo">
        <a href="../Productos/productos.php">
            <img src="Imagenes/ViveConfort.png" class="logo-img" alt="Logo">
        </a>
    </div>
    <div class="header-derecha">
        <div class="iconos-header">
            <a href="http://localhost/viveconfort/inicioS/favoritos.php" class="btn-favorito-emoji">❤</a>
            <a href="http://localhost/viveconfort/Carrito/carrito.php" class="btn-carrito-emoji">🛒</a>
            <a href="http://localhost/viveconfort/inicioS/perfil.php" class="icon-link">
                <img src="Imagenes/persona.png" alt="Cuenta" class="header-icon-img">
            </a>    
        </div>
    </div>
</header>

<div id="contenedor-principal">
    <div class="detalle-grid">
        <div class="seccion-imagen">
            <a href="../Productos/productos.php" class="flecha-regresar-top">
                <span>←</span>
            </a>
            
            <div class="contenedor-galeria">
                <div class="cuadro-foto">
                    <img src="<?php echo $imagen1; ?>" id="foto-principal" alt="Producto">
                </div>

                <?php if ($imagen2): ?>
                <div class="miniaturas">
                    <img src="<?php echo $imagen1; ?>" class="thumb active" onclick="cambiarFoto('<?php echo $imagen1; ?>', this)">
                    <img src="<?php echo $imagen2; ?>" class="thumb" onclick="cambiarFoto('<?php echo $imagen2; ?>', this)">
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="seccion-info">
            <h2 class="nombre-producto"><?php echo htmlspecialchars($row['nombre_producto']); ?></h2>
            <p class="marca-producto"><?php echo htmlspecialchars($row['marca']); ?></p>
            
            <p class="precio-producto">
                <?php echo "$" . number_format(floatval($row['precio']), 0, ',', '.'); ?>
            </p>

            <div class="tonos-area">
                <label class="tonalidades-label">Elegir Tono:</label>
                <select class="selector-tonos" id="tono-seleccionado">
                    <?php if (empty($lista_tonos)): ?>
                        <option>Tono único</option>
                    <?php else: ?>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($lista_tonos as $tono): ?>
                            <option value="<?php echo trim($tono); ?>"><?php echo trim($tono); ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <p class="stock">Disponibilidad: <strong>En stock (<?php echo $row['cantidad']; ?> unidades)</strong></p>

            <div class="controles-compra">
                <div class="selector-cantidad">
                    <button type="button" onclick="changeQty(-1)">-</button>
                    <input type="text" id="cantidad" value="1" readonly>
                    <button type="button" onclick="changeQty(1)">+</button>
                </div>

                <button type="button" class="btn-añadir" onclick="agregarAlCarrito()">Añadir al carrito</button>
                
                <span class="btn-corazon" id="corazon-favorito" 
                      style="color: <?php echo $es_favorito ? '#ad1457' : 'black'; ?>; cursor: pointer;" 
                      onclick="gestionarFavorito()">❤</span>
            </div>
        </div>
    </div>
</div>

<script>
// 1. Funciones de Interfaz
function cambiarFoto(nuevaSrc, elemento) {
    document.getElementById('foto-principal').src = nuevaSrc;
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    elemento.classList.add('active');
}

function changeQty(v) {
    let i = document.getElementById('cantidad');
    let n = parseInt(i.value) + v;
    if (n >= 1) i.value = n;
}

// 2. Lógica AJAX para Carrito
function agregarAlCarrito() {
    const nombreProd = document.querySelector('.nombre-producto').innerText;
    const cantidadProd = document.getElementById('cantidad').value;
    const tonoSeleccionado = document.getElementById('tono-seleccionado').value;

    if (tonoSeleccionado === "") {
        alert("Por favor, selecciona un tono antes de añadir al carrito.");
        return;
    }

    const datos = new FormData();
    datos.append('nombre', nombreProd);
    datos.append('cantidad', cantidadProd);
    datos.append('tono', tonoSeleccionado);

    fetch('../Carrito/agregar_al_carrito.php', {
        method: 'POST',
        body: datos
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            alert('¡Producto añadido! Tono: ' + tonoSeleccionado + ' 🛒');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => console.error("Error:", err));
}

// 3. Lógica AJAX para Favoritos (Toggle: Agregar/Quitar)
function gestionarFavorito() {
    const corazon = document.getElementById('corazon-favorito');
    const nombreProd = document.querySelector('.nombre-producto').innerText;

    // Detectar si ya es favorito por el color (Rosa ViveConfort)
    const esFavoritoActualmente = corazon.style.color === 'rgb(173, 20, 87)' || corazon.style.color === '#ad1457';
    
    // Si ya es rosa, llamamos a borrar. Si no, a agregar.
    const archivoDestino = esFavoritoActualmente ? '../inicioS/borrar_favorito_ajax.php' : '../inicioS/agregar_favorito.php';

    const datos = new FormData();
    datos.append('nombre', nombreProd);

    fetch(archivoDestino, {
        method: 'POST',
        body: datos
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            if (esFavoritoActualmente) {
                corazon.style.color = 'black';
                alert("Eliminado de favoritos");
            } else {
                corazon.style.color = '#ad1457';
                alert("¡Añadido a favoritos! ❤");
            }
        } else if(data.status === 'exists') {
            corazon.style.color = '#ad1457';
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => console.error("Error:", err));
}
</script>

</body>
</html>