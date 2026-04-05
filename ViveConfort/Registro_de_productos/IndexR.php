<?php
session_start(); 

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin_global' && $_SESSION['rol'] !== 'admin_local')) {    // Salimos de 'Creación_de_usuario' e entramos a 'inicioS'
    header("Location: ../inicioS/login.php");
    exit();
} 

$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "viveconfort";
$conexion = new mysqli($host, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consultar todos los productos, incluyendo el campo imagen
$sql = "SELECT * FROM productos";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Inventario - Vive Confort</title>
    <link rel="stylesheet" href="style.registroP.css">
</head>
<body>

<div id="contenedor">
    <h1 style="font-family: 'Times New Roman', Times, serif; font-style: italic;">Gestión de Inventario</h1>
    
    <div class="botones-container">
        <a href="registro.php" class="btn-accion">➕ Registrar Nuevo Producto</a>
    </div>

    <div class="buscador-caja">
        <input type="text" id="busqueda" class="input-busqueda" placeholder="🔍 Buscar producto..">
    </div>

    <table>
        <thead>
            <tr>
                <th>Foto</th> <th>Nombre</th>
                <th>Categoría</th>
                <th>Marca</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Tonalidades</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-datos">
            <?php
            if ($resultado && $resultado->num_rows > 0) {
                while($row = $resultado->fetch_assoc()) {
                    $nombreParaUrl = urlencode($row['nombre_producto']);
                    
                    // --- LÓGICA PARA MOSTRAR LA IMAGEN DESDE LA BASE DE DATOS ---
                    $fotoTag = "<span>Sin foto</span>";
                    if (!empty($row['imagen'])) {
                        // Convertimos los datos binarios a formato Base64 para que el navegador los lea
                        $datosImagen = base64_encode($row['imagen']);
                        $fotoTag = '<img src="data:image/jpeg;base64,' . $datosImagen . '" style="width: 60px; height: 60px; border-radius: 8px; border: 1px solid #f8bbd0; object-fit: cover;">';
                    }
                    
                    echo "<tr>
                            <td>" . $fotoTag . "</td>
                            <td>" . htmlspecialchars($row['nombre_producto']) . "</td>
                            <td>" . htmlspecialchars($row['categoria']) . "</td>
                            <td>" . htmlspecialchars($row['marca']) . "</td>
                            <td>$" . number_format((float)$row['precio'], 0, ',', '.') . "</td>
                            <td>" . htmlspecialchars($row['cantidad']) . "</td>
                            <td>" . htmlspecialchars($row['tonalidades']) . "</td>
                            <td>
                                <a href='editar.php?nombre=$nombreParaUrl' class='btn-edit'>Editar</a>
                                <a onclick='eliminar(\"$nombreParaUrl\")' class='btn-delete'>Eliminar</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8' style='text-align:center;'>No hay productos registrados en ViveConfort.</td></tr>";
            }
            ?>
        </tbody>
    </table>
        <div class="footer-acciones">
            <a href="../Productos/productos.php" class="btn-volver">Volver a la tienda</a>
            <a href="../inicioS/perfil.php" class="btn-perfil">Volver al perfil</a>
       </div>
</div>

<script>
// Filtro de búsqueda en tiempo real
document.getElementById('busqueda').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tabla-datos tr');
    filas.forEach(fila => {
        let texto = fila.innerText.toLowerCase();
        if(fila.cells.length > 1) {
            fila.style.display = texto.includes(filtro) ? '' : 'none';
        }
    });
});

// Función eliminar usando el nombre
function eliminar(nombre) {
    if(confirm('¿Deseas eliminar este producto de ViveConfort permanentemente?')) {
        window.location.href = 'eliminar.php?nombre=' + nombre;
    }
}
</script>

</body>
</html>