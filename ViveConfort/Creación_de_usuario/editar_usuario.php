<?php
$host = "localhost"; $usuario = "root"; $clave = ""; $bd = "viveconfort";
$conexion = new mysqli($host, $usuario, $clave, $bd);

$email_get = $_GET['email'] ?? '';
$usuario_data = null;

if ($email_get !== '') {
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email_get);
    $stmt->execute();
    $usuario_data = $stmt->get_result()->fetch_assoc();
}

if (!$usuario_data) { header("Location: indexR.php"); exit; }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombres = $_POST['nombres']; $apellidos = $_POST['apellidos'];
    $pais = $_POST['pais']; $depto = $_POST['departamento'];
    $ciudad = $_POST['ciudad']; $cp = $_POST['codigoPostal'];
    $dir = $_POST['direccion']; $email_n = $_POST['email'];
    $tel = $_POST['telefono']; $pass = $_POST['contraseña'];
    $rol = $_POST['rol']; $email_antiguo = $_POST['email_orig'];

    $sql = "UPDATE usuarios SET nombres=?, apellidos=?, pais=?, departamento=?, ciudad=?, codigoPostal=?, direccion=?, email=?, telefono=?, contraseña=?, rol=? WHERE email=?";
    $stmt_up = $conexion->prepare($sql);
    $stmt_up->bind_param("ssssssssssss", $nombres, $apellidos, $pais, $depto, $ciudad, $cp, $dir, $email_n, $tel, $pass, $rol, $email_antiguo);
    
    if ($stmt_up->execute()) {
        echo "<script>alert('¡Datos actualizados!'); window.location.href='indexR.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Editar Usuario - Vive Confort</title>
    <link rel="stylesheet" href="style.U.css?v=<?php echo time(); ?>">
</head>
<body>

<div id="contenedor">
    <h1>Editar Información</h1>
    
    <form method="post">
        <input type="hidden" name="email_orig" value="<?php echo htmlspecialchars($usuario_data['email']); ?>">
        
        <div class="grid-campos">
            <div>
                <label>Nombres</label>
                <input type="text" name="nombres" value="<?php echo htmlspecialchars($usuario_data['nombres']); ?>" required>
            </div>
            <div>
                <label>Apellidos</label>
                <input type="text" name="apellidos" value="<?php echo htmlspecialchars($usuario_data['apellidos']); ?>" required>
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($usuario_data['email']); ?>" required>
            </div>
            <div>
                <label>Teléfono</label>
                <input type="text" name="telefono" value="<?php echo htmlspecialchars($usuario_data['telefono']); ?>" required>
            </div>

            <div>
                <label>País</label>
                <input type="text" name="pais" value="<?php echo htmlspecialchars($usuario_data['pais']); ?>" required>
            </div>
            <div>
                <label>Ciudad</label>
                <input type="text" name="ciudad" value="<?php echo htmlspecialchars($usuario_data['ciudad']); ?>" required>
            </div>

            <div>
                <label>Departamento</label>
                <input type="text" name="departamento" value="<?php echo htmlspecialchars($usuario_data['departamento']); ?>" required>
            </div>
            <div>
                <label>Código Postal</label>
                <input type="text" name="codigoPostal" value="<?php echo htmlspecialchars($usuario_data['codigoPostal']); ?>" required>
            </div>

            <div class="campo-completo">
                <label>Dirección</label>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($usuario_data['direccion']); ?>" required>
            </div>

            <div class="campo-completo">
                <label>Contraseña</label>
                <input type="text" name="contraseña" value="<?php echo htmlspecialchars($usuario_data['contraseña']); ?>" required>
            </div>

            <div class="campo-completo">
                <label>Rol</label>
                <input type="text" name="rol" value="<?php echo htmlspecialchars($usuario_data['rol']); ?>" required>
            </div>
        </div>

        <div class="botones-container">
            <button type="submit" class="btn-accion btn-guardar">Guardar Cambios</button>
            <a href="indexR.php" class="btn-accion btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>