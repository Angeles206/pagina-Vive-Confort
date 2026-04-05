<?php
$host = "localhost"; $usuario = "root"; $clave = ""; $bd = "viveconfort";
$conexion = new mysqli($host, $usuario, $clave, $bd);

if ($conexion->connect_error) { die("Fallo de conexión: " . $conexion->connect_error); }

// 1. CARGAR DATOS DEL PRODUCTO
$producto = null;
$nombre_get = $_GET['nombre'] ?? '';

if ($nombre_get !== '') {
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE nombre_producto = ?");
    $stmt->bind_param("s", $nombre_get);
    $stmt->execute();
    $producto = $stmt->get_result()->fetch_assoc();
}

if (!$producto) { header("Location: indexR.php"); exit; }

// 2. PROCESAR ACTUALIZACIÓN
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevo_nombre   = trim($_POST["nombre_producto"]);
    $categoria      = trim($_POST["categoria"]);
    $marca          = trim($_POST["marca"]);
    
    // --- CORRECCIÓN DE PRECIO ---
    // Recibimos el valor y eliminamos cualquier punto o coma accidental para que MySQL guarde el número limpio
    $precio_input   = $_POST["precio"];
    $precio         = str_replace(['.', ','], '', $precio_input); 
    
    $stock          = $_POST["stock"];
    $tonalidades    = trim($_POST["tonalidades"]);
    $nombre_antiguo = $_POST["nombre_original_hidden"];

    $sql = "UPDATE productos SET nombre_producto=?, categoria=?, marca=?, precio=?, cantidad=?, tonalidades=?";
    $tipos = "sssdis";
    $valores = [$nuevo_nombre, $categoria, $marca, $precio, $stock, $tonalidades];
    $imagenes_binarias = [];

    if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] === 0) {
        $sql .= ", imagen=?";
        $tipos .= "b";
        $imagenes_binarias[] = ['index' => count($valores), 'data' => file_get_contents($_FILES['nueva_imagen']['tmp_name'])];
        $valores[] = null;
    }

    if (isset($_FILES['nueva_imagen2']) && $_FILES['nueva_imagen2']['error'] === 0) {
        $sql .= ", imagen2=?";
        $tipos .= "b";
        $imagenes_binarias[] = ['index' => count($valores), 'data' => file_get_contents($_FILES['nueva_imagen2']['tmp_name'])];
        $valores[] = null;
    }

    $sql .= " WHERE nombre_producto=?";
    $tipos .= "s";
    $valores[] = $nombre_antiguo;

    $stmt_update = $conexion->prepare($sql);
    $stmt_update->bind_param($tipos, ...$valores);

    foreach ($imagenes_binarias as $img) {
        $stmt_update->send_long_data($img['index'], $img['data']);
    }

    if ($stmt_update->execute()) {
        echo "<script>alert('¡Producto actualizado con éxito!'); window.location.href = 'indexR.php';</script>";
        exit;
    } else {
        echo "Error al guardar: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Editar Producto - ViveConfort</title>
    <link rel="stylesheet" href="style.registroP.css">
</head>
<body>

<div id="contenedor">
    <h1 style="font-family: serif; font-style: italic; text-align: center; color: #ad1457;">Editar Producto</h1>
    
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="nombre_original_hidden" value="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
        
        <ul>
            <li><label class="titulo">Nombre</label>
                <input type="text" name="nombre_producto" value="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" required>
            </li>
            <li><label class="titulo">Categoría</label>
                <input type="text" name="categoria" value="<?php echo htmlspecialchars($producto['categoria']); ?>" required>
            </li>
            <li><label class="titulo">Marca</label>
                <input type="text" name="marca" value="<?php echo htmlspecialchars($producto['marca']); ?>" required>
            </li>
            
            <li><label class="titulo">Precio</label>
                <input type="text" id="input-precio" name="precio" 
                       value="<?php echo number_format((float)$producto['precio'], 0, ',', '.'); ?>" 
                       required onkeyup="formatearPrecio(this)">
            </li>

            <li><label class="titulo">Stock</label>
                <input type="number" name="stock" value="<?php echo $producto['cantidad']; ?>" required>
            </li>
            <li><label class="titulo">Tonalidades</label>
                <input type="text" name="tonalidades" value="<?php echo htmlspecialchars($producto['tonalidades']); ?>">
            </li>

            <li>
                <label class="titulo">Imagen Principal</label>
                <div class="controles">
                    <?php if(!empty($producto['imagen'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($producto['imagen']); ?>" style="width: 80px; margin-bottom: 5px; border-radius: 5px;">
                    <?php endif; ?>
                    <input type="file" name="nueva_imagen" accept="image/*">
                </div>
            </li>

            <li>
                <label class="titulo">Imagen Secundaria</label>
                <div class="controles">
                    <?php if(!empty($producto['imagen2'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($producto['imagen2']); ?>" style="width: 80px; margin-bottom: 5px; border-radius: 5px;">
                    <?php endif; ?>
                    <input type="file" name="nueva_imagen2" accept="image/*">
                </div>
            </li>

            <div class="botones-container" style="display: flex; gap: 15px; margin-top: 20px; justify-content: center;">
                <button type="submit" class="btn-accion btn-guardar" style="background: #ad1457; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer;">Guardar Cambios</button>
                <a href="indexR.php" class="btn-accion btn-cancelar" style="background: #f8bbd0; padding: 10px 20px; text-decoration: none; border-radius: 8px; color: black; border: 1px solid #000;">Cancelar</a>
            </div>
        </ul>
    </form>
</div>

<script>
// Esta función pone los puntos de miles automáticamente mientras escribes
function formatearPrecio(input) {
    let num = input.value.replace(/\D/g, ""); // Elimina todo lo que no sea número
    if (num) {
        input.value = new Intl.NumberFormat('es-CO').format(num);
    } else {
        input.value = "";
    }
}
</script>

</body>
</html>