<?php
session_start();
$host = "localhost"; $usuario = "root"; $clave = ""; $bd = "viveconfort";

$mensajeEstado = $_SESSION['mensajeEstado'] ?? "";
$tipoMensaje = $_SESSION['tipoMensaje'] ?? "";
unset($_SESSION['mensajeEstado'], $_SESSION['tipoMensaje']); 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conexion = new mysqli($host, $usuario, $clave, $bd);
    if ($conexion->connect_error) {
        $mensajeEstado = "Error de conexión"; $tipoMensaje = "error";
    } else {
        $nombre = trim($_POST["nombre_producto"]);
        $cat = trim($_POST["categoria"]);
        $marca = trim($_POST["marca"]);
        $precio = $_POST["precio"];
        $cant = $_POST["cantidad"];
        $tonos = trim($_POST["tonalidades"]);

        // Procesar Imágenes
        $img1 = (isset($_FILES['img1']) && $_FILES['img1']['error'] === 0) ? file_get_contents($_FILES['img1']['tmp_name']) : null;
        $img2 = (isset($_FILES['img2']) && $_FILES['img2']['error'] === 0) ? file_get_contents($_FILES['img2']['tmp_name']) : null;

        $stmt = $conexion->prepare("INSERT INTO productos (nombre_producto, categoria, marca, precio, cantidad, tonalidades, imagen, imagen2) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $null = NULL;
        $stmt->bind_param("sssdisbb", $nombre, $cat, $marca, $precio, $cant, $tonos, $null, $null);
        
        if($img1) $stmt->send_long_data(6, $img1);
        if($img2) $stmt->send_long_data(7, $img2);

        if ($stmt->execute()) {
            $_SESSION['mensajeEstado'] = "Producto guardado con éxito.";
            $_SESSION['tipoMensaje'] = "success";
        } else {
            $_SESSION['mensajeEstado'] = "Error al guardar.";
            $_SESSION['tipoMensaje'] = "error";
        }
        $stmt->close(); $conexion->close();
    }
    header("Location: " . $_SERVER['PHP_SELF']); exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" /><title>Ingreso - Vive Confort</title>
    <link rel="stylesheet" href="style.registroP.css">
</head>
<body>
<div id="contenedor">
    <?php if ($mensajeEstado !== ""): ?>
        <div class="alerta <?php echo ($tipoMensaje === 'success') ? 'alerta-exito' : 'alerta-error'; ?>"><?php echo $mensajeEstado; ?></div>
    <?php endif; ?>
    <h1>Ingreso de Producto</h1>
    <form method="post" enctype="multipart/form-data">
        <ul>
            <li><label class="titulo">Nombre</label><input type="text" name="nombre_producto" required></li>
            <li><label class="titulo">Categoría</label><input type="text" name="categoria" required></li>
            <li><label class="titulo">Marca</label><input type="text" name="marca" required></li>
            <li><label class="titulo">Precio</label><input type="number" step="0.01" name="precio" required></li>
            <li><label class="titulo">Cantidad</label><input type="number" name="cantidad" required></li>
            <li><label class="titulo">Tonalidades</label><input type="text" name="tonalidades"></li>
            <li><label class="titulo">Foto 1 (Principal)</label><input type="file" name="img1" accept="image/*" required></li>
            <li><label class="titulo">Foto 2 (Opcional)</label><input type="file" name="img2" accept="image/*"></li>
            <div class="botones-container"><button type="submit" class="btn-accion">Guardar producto</button></div>
        </ul>
    </form>
</div>
</body>
</html>