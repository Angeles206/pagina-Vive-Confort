<?php
$host = "localhost"; $usuario = "root"; $clave = ""; $bd = "viveconfort";
$conexion = new mysqli($host, $usuario, $clave, $bd);

if ($conexion->connect_error) { die("Error de conexión: " . $conexion->connect_error); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombres      = trim($_POST["nombres"]);
    $apellidos    = trim($_POST["apellidos"]);
    $pais         = trim($_POST["pais"]);
    $departamento = trim($_POST["departamento"]);
    $ciudad       = trim($_POST["ciudad"]);
    $codigoPostal = trim($_POST["codigoPostal"]);
    $direccion    = trim($_POST["direccion"]);
    $email        = trim($_POST["email"]);
    $telefono     = trim($_POST["telefono"]);
    $contraseña   = $_POST["contraseña"];

    $sql = "INSERT INTO usuarios (nombres, apellidos, pais, departamento, ciudad, codigoPostal, direccion, email, telefono, contraseña) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssssss", $nombres, $apellidos, $pais, $departamento, $ciudad, $codigoPostal, $direccion, $email, $telefono, $contraseña);

    if ($stmt->execute()) {
        echo "<script>alert('¡Usuario registrado con éxito!'); window.location.href = 'indexR.php';</script>";
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
    <title>Registrar Usuario - Vive Confort</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.U.css?v=999">
</head>
<body>

<div id="contenedor">
    <h1>Nuevo Usuario</h1>
    
    <form method="post">
        <div class="grid-campos">
            <div>
                <label>Nombres</label>
                <input type="text" name="nombres" required>
            </div>
            <div>
                <label>Apellidos</label>
                <input type="text" name="apellidos" required>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label>Teléfono</label>
                <input type="text" name="telefono" required>
            </div>
            <div>
                <label>País</label>
                <input type="text" name="pais" required>
            </div>
            <div>
                <label>Departamento</label>
                <input type="text" name="departamento" required>
            </div>
            <div>
                <label>Ciudad</label>
                <input type="text" name="ciudad" required>
            </div>
            <div>
                <label>Código postal</label>
                <input type="text" name="codigoPostal" required>
            </div>

            <div class="campo-completo">
                <label>Dirección</label>
                <input type="text" name="direccion" required> </div>
                        
            <div class="campo-completo">
                <label>Contraseña</label>
                <input type="password" name="contraseña" placeholder="********" required>
            </div>
        </div>

        <div class="botones-container">
            <button type="submit" class="btn-accion btn-guardar">Registrar Usuario</button>
            <a href="indexR.php" class="btn-accion btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>