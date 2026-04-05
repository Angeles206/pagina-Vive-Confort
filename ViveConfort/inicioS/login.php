<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | ViveConfort</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.Sesion.css"> 
</head>
<body>

<header class="header-tienda">
    <div class="logo">
        <a href="http://localhost/viveconfort/Inicio/Inicio.php">
            <img src="imagenes/ViveConfort.png" class="logo-img" alt="Logo">
        </a>
    </div>
</header>

<div id="contenedor"> <h1 class="titulo-login">ViveConfort</h1>
    
    <div class="controles"> <form action="validar_login.php" method="POST">
            
            <div class="completo"> <label for="email">Correo electrónico</label>
                <input type="email" name="email" id="email" required placeholder="ejemplo@correo.com">
            </div>

            <div class="completo">
                <label for="password">Contraseña</label>
                <input type="password" name="pass" id="password" required placeholder="Tu contraseña">
            </div>
            <div class="botones-container">
                <button type="submit" class="btn-ingresar">Ingresar</button>

                <div class="fila-botones">
                    <a href="http://localhost/viveconfort/Creación_de_usuario/Login.php" class="btn-secundario">Regístrate aquí</a>
                    <a href="http://localhost/viveconfort/recuperacion_contraseña/restablecer.php" class="btn-secundario">Olvide mi contraseña</a>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>