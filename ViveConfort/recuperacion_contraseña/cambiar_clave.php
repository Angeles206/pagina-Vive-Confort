<?php
session_start();
if (!isset($_SESSION['codigo_verificado'])) { header("Location: solicitar_recuperacion.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Clave | ViveConfort</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.Sesion.css">
</head>

    <header class="header-tienda">
        <div class="logo">
            <a href="../Productos/productos.php">
                <img src="Imagenes/ViveConfort.png" class="logo-img" alt="Logo ViveConfort">
            </a>
        </div>
    </header>

<body>
    <div id="contenedor">
        <h1 class="titulo-login">Nueva Contraseña</h1>
        <div class="controles">
            <form action="actualizar_final.php" method="POST">
                <div class="completo">
                    <label>Nueva contraseña</label>
                    <input type="password" name="nueva_clave" required>
                </div>
                <div class="completo">
                    <label>Confirmar contraseña</label>
                    <input type="password" name="confirmar_clave" required>
                </div>
                <div class="botones-container">
                    <button type="submit" class="btn-ingresar">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>