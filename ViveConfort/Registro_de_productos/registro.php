<?php
$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "viveconfort";
$conexion = new mysqli($host, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre      = trim($_POST["nombre_producto"]);
    $categoria   = trim($_POST["categoria"]);
    $marca       = trim($_POST["marca"]);
    $precio      = $_POST["precio"];
    $cantidad    = $_POST["cantidad"];
    $tonalidades = trim($_POST["tonalidades"]);

    // --- LÓGICA PARA PROCESAR LAS 2 IMÁGENES ---
    $imagenes = [null, null]; // Espacio para imagen 1 e imagen 2
    $campos_foto = ['imagen_producto_1', 'imagen_producto_2'];

    foreach ($campos_foto as $indice => $campo) {
        if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] === 0) {
            // Validar que cada imagen no supere los 2MB
            if ($_FILES[$campo]['size'] > 2097152) {
                echo "<script>alert('Error: La foto " . ($indice + 1) . " es muy pesada (Máximo 2MB).'); window.history.back();</script>";
                exit;
            }
            $imagenes[$indice] = file_get_contents($_FILES[$campo]['tmp_name']);
        }
    }

    // Insertar en la tabla productos (Asegúrate de tener columnas 'imagen' e 'imagen2')
    $sql = "INSERT INTO productos (nombre_producto, categoria, marca, precio, cantidad, tonalidades, imagen, imagen2) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    
    $null = NULL; 
    // "b" dos veces al final para los dos campos LONGBLOB
    $stmt->bind_param("sssdisbb", $nombre, $categoria, $marca, $precio, $cantidad, $tonalidades, $null, $null);
    
    // Enviamos los datos binarios de ambas fotos
    $stmt->send_long_data(6, $imagenes[0]); 
    $stmt->send_long_data(7, $imagenes[1]); 

    if ($stmt->execute()) {
        echo "<script>
                alert('¡Producto y las 2 fotos registrados con éxito!');
                window.location.href = 'indexR.php';
              </script>";
        exit;
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Registrar Producto - Vive Confort</title>
    <link rel="stylesheet" href="style.registroP.css">
</head>
<body>

<div id="contenedor">
    <h1 class="titulo-login" style="font-family: 'Times New Roman', Times, serif; font-style: italic;">Nuevo Producto</h1>
    
    <form method="post" action="registro.php" enctype="multipart/form-data">
        <ul style="list-style: none; padding: 0;">
            <li class="completo">
                <label class="titulo">Nombre</label>
                <div class="controles"><input type="text" name="nombre_producto" placeholder="Ej: Labial Matte" required></div>
            </li>
            <li class="completo">
                <label class="titulo">Categoría</label>
                <div class="controles"><input type="text" name="categoria" placeholder="Ej: Labiales" required></div>
            </li>
            <li class="completo">
                <label class="titulo">Marca</label>
                <div class="controles"><input type="text" name="marca" placeholder="Ej: Kiss Beauty" required></div>
            </li>
            <li class="completo">
                <label class="titulo">Precio</label>
                <div class="controles"><input type="number" name="precio" step="0.01" placeholder="0.00" required></div>
            </li>
            <li class="completo">
                <label class="titulo">Cantidad</label>
                <div class="controles"><input type="number" name="cantidad" placeholder="Cantidad en stock" required></div>
            </li>
            <li class="completo">
                <label class="titulo">Tonalidades</label>
                <div class="controles"><input type="text" name="tonalidades" placeholder="Colores separados por comas"></div>
            </li>
            
            <li class="completo">
                <label class="titulo">Foto Principal (Requerida)</label>
                <div class="controles">
                    <input type="file" name="imagen_producto_1" accept="image/*" required>
                </div>
            </li>

            <li class="completo">
                <label class="titulo">Foto Secundaria (Opcional)</label>
                <div class="controles">
                    <input type="file" name="imagen_producto_2" accept="image/*">
                </div>
            </li>

            <div class="botones-container">
                <button type="submit" class="btn-accion btn-guardar">Agregar al Inventario</button>
                <a href="indexR.php" class="btn-accion btn-cancelar">Volver</a>
            </div>
        </ul>
    </form>
</div>

</body>
</html>