<?php
$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "viveconfort";
$conexion = new mysqli($host, $usuario, $clave, $bd);

if (isset($_GET['nombre'])) {
    $nombre = $_GET['nombre'];
    $stmt = $conexion->prepare("DELETE FROM productos WHERE nombre_producto = ?");
    $stmt->bind_param("s", $nombre);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Producto eliminado');
                window.location.href = 'indexR.php';
              </script>";
    } else {
        echo "Error al eliminar: " . $conexion->error;
    }
} else {
    header("Location: indexR.php");
}
?>