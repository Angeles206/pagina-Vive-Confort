<?php
$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "viveconfort";
$conexion = new mysqli($host, $usuario, $clave, $bd);

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    
    // Preparamos la eliminación usando el email
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Usuario eliminado correctamente');
                window.location.href = 'indexR.php';
              </script>";
    } else {
        echo "Error al eliminar: " . $conexion->error;
    }
} else {
    header("Location: indexR.php");
}
?>