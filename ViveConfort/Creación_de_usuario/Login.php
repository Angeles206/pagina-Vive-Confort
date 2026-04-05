<?php
session_start();

$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "viveconfort";

$mensajeEstado = $_SESSION['mensajeEstado'] ?? "";
$tipoMensaje = $_SESSION['tipoMensaje'] ?? "";

unset($_SESSION['mensajeEstado'], $_SESSION['tipoMensaje']); 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conexion = new mysqli($host, $usuario, $clave, $bd);

    if ($conexion->connect_error) {
        $mensajeEstado = "Error de conexión a la base de datos";
        $tipoMensaje = "error";
    } else {        
        $nombres = trim($_POST["nombres"] ?? '');
        $apellidos = trim($_POST["apellidos"] ?? '');
        $pais = trim($_POST["pais"] ?? '');
        $departamento = trim($_POST["departamento"] ?? '');
        $ciudad = trim($_POST["ciudad"] ?? '');
        $codigoPostal = trim($_POST["codigoPostal"] ?? '');
        $direccion = trim($_POST["direccion"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $telefono = trim($_POST["telefono"] ?? '');
        $contraseña = trim($_POST["contraseña"] ?? '');

        if ($nombres === "" || $apellidos === "" || $pais === "" || $departamento === "" || $ciudad === "" || $codigoPostal === "" || $direccion === "" || $email === "" || $telefono === "" || $contraseña === "") {
            $mensajeEstado = "Algunos campos están sin llenar.";
            $tipoMensaje = "error";
        } else {
            $stmt = $conexion->prepare(
                "INSERT INTO usuarios (nombres, apellidos, pais, departamento, ciudad, codigoPostal, direccion, email, telefono, contraseña) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("ssssssssss", $nombres, $apellidos, $pais, $departamento, $ciudad, $codigoPostal, $direccion, $email, $telefono, $contraseña);

            if ($stmt->execute()) {
                $mensajeEstado = "¡Gracias! Tu información se guardó correctamente.";
                $tipoMensaje = "success";
            } else {
                $mensajeEstado = "Ocurrió un error al guardar tus datos: " . $conexion->error;
                $tipoMensaje = "error";
            }
            $stmt->close();
        } 
        $conexion->close(); 
    }

    $_SESSION['mensajeEstado'] = $mensajeEstado;
    $_SESSION['tipoMensaje'] = $tipoMensaje;

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html> 
<html>
<head>
    <meta charset="utf-8" />
    <title>Creación de usuario</title> 
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.CU.css">
</head>
<body>

<header class="header-tienda">
    <div class="logo">
        <a href="../Productos/productos.php">
            <img src="imagenes/ViveConfort.png" class="logo-img" alt="Logo">
        </a>
    </div>
</header>

<div id="contenedor">

    <?php if ($mensajeEstado): ?>
        <div class="alerta <?php echo $tipoMensaje; ?>">
            <?php echo $mensajeEstado; ?>
        </div>
    <?php endif; ?>

    <h1>Creación de usuario</h1>
    
    <form method="post" action="">
    <ul>
    <li>
      <label class="titulo">Nombre y apellidos</label>
      <div class="controles">
        <span class="completo">
          <input type="text" name="nombres" required>
          <label>Nombres</label>
        </span>
        <span class="completo">
          <input type="text" name="apellidos" required>
          <label>Apellidos</label>
        </span>
      </div>
    </li>
    
    <li>
      <label class="titulo">Dirección</label>
      <div class="controles">
        <span class="mitad">
          <input type="text" name="pais" required>
          <label>País</label>
        </span>
        <span class="mitad">
          <input type="text" name="departamento" required>
          <label>Departamento</label>
        </span>
        <span class="mitad">
          <input type="text" name="ciudad" required>
          <label>Ciudad</label>
        </span> 
        <span class="mitad">
          <input type="text" name="codigoPostal" required>
          <label>Código postal</label>
        </span>
        <span class="completo">
          <input type="text" name="direccion" required>
          <label>Calle, número, piso</label>
        </span>     
      </div>
    </li>
    
    <li>
      <label class="titulo">Email</label>
      <div class="controles">
        <span class="completo">
          <input name="email" type="email" required>
        </span>
      </div>
    </li>
    
    <li>
      <label class="titulo">Teléfono</label>
      <div class="controles">
        <span class="completo">
          <input type="text" name="telefono" required>
          <label>Número de teléfono</label>
        </span>
      </div>
    </li>

    <li>
      <label class="titulo">Contraseña</label>
      <div class="controles">
        <span class="mitad">
          <input type="password" name="contraseña" required>
          <label>Digita tu contraseña</label>
        </span>
        <span class="mitad">
          <input type="password" name="confirmación" required>
          <label>Confirma tu contraseña</label>
        </span>
     </div>
    </li>

    <div class="botones-container">
      <a href="http://localhost/viveconfort/inicioS/login.php" class="btn-accion">Volver</a>
    
      <button type="submit" class="btn-accion">Crear usuario</button>
    </div>

    </ul>
    </form>
    
</div>
</body>
</html>