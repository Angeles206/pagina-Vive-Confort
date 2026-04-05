<?php
session_start();

// Si no hay un email en sesión, regresamos al inicio del proceso
if (!isset($_SESSION['email_recuperacion'])) {
    header("Location: restablecer.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código | ViveConfort</title>
    <link rel="stylesheet" href="style.Sesion.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header-tienda">
        <div class="logo">
            <a href="../Productos/productos.php">
                <img src="Imagenes/ViveConfort.png" class="logo-img" alt="Logo ViveConfort">
            </a>
        </div>
    </header>

<div id="contenedor">
    <h1 class="titulo-login">Verificación</h1>
    
    <div class="controles">
        <p style="text-align: center; font-family: serif; margin-bottom: 20px;">
            Ingresa el código de 6 dígitos enviado a:<br>
            <strong><?php echo htmlspecialchars($_SESSION['email_recuperacion']); ?></strong>
        </p>

        <form action="validar_codigo.php" method="POST">
            <div class="completo">
                <label for="codigo">Código de Seguridad</label>
                <input type="text" 
                       name="codigo_ingresado" 
                       id="codigo" 
                       required 
                       maxlength="6" 
                       placeholder="000000"
                       style="text-align: center; letter-spacing: 8px; font-size: 1.5em; font-weight: bold;">
            </div>

            <div class="botones-container">
                <button type="submit" class="btn-ingresar">Validar Código</button>
                
                <div class="fila-botones">
                    <a href="restablecer.php" class="btn-secundario">Reenviar código</a>                </div>
                </div>
        </form>
    </div>
</div>

</body>
</html>