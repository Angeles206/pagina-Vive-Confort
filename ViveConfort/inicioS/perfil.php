<?php
session_start();

// EL CANDADO: Si no hay sesión, nadie entra
if (!isset($_SESSION['correo'])) {
    header("Location: login.php");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "viveconfort");
$correo_sesion = $_SESSION['correo'];

$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $correo_sesion);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Perfil - ViveConfort</title>
    <link rel="stylesheet" href="styleP.css"> 
</head>
<body>

<header class="header-tienda">
    <div class="logo">
        <a href="../Productos/productos.php">
            <img src="imagenes/ViveConfort.png" class="logo-img" alt="Logo">
        </a>
    </div>
</header>

<div id="contenedor-principal">
    <div class="perfil-layout">
        <section class="col-izq">
            <h1 class="titulo-seccion">Perfil</h1>
            <div class="marco-foto">
                <img src="imagenes/persona.png" alt="Usuario" class="foto-perfil">
            </div>
            
            <div class="acciones-perfil">
                <button class="tab btn-especial" onclick="window.location.href='../Productos/productos.php'">Volver a los productos</button>

                <?php if (isset($user['rol'])): ?>
                    <?php if ($user['rol'] === 'admin_global'): ?>
                        <button class="tab btn-especial" onclick="window.location.href='../Creación_de_usuario/IndexR.php'">Gestión de Usuarios</button>
                        <button class="tab btn-especial" onclick="window.location.href='../Registro_de_productos/IndexR.php'">Gestión de Productos</button>
                    <?php elseif ($user['rol'] === 'admin_local'): ?>
                        <button class="tab btn-especial" onclick="window.location.href='../Registro_de_productos/IndexR.php'">Gestión de Productos</button>
                    <?php endif; ?>
                <?php endif; ?>

                <button class="tab btn-especial btn-salir" onclick="window.location.href='logout.php'">Cerrar sesión</button>
            </div>
        </section>

        <section class="col-der">
            <nav class="pestanas">
                <button class="tab" onclick="window.location.href='../Carrito/carrito.php'">Carrito</button>
                <button class="tab" onclick="window.location.href='http://localhost/viveconfort/inicioS/favoritos.php'">Favoritos</button>
            </nav>

            <h2 class="nombre-usuario">
                <?php echo htmlspecialchars($user['nombres'] . " " . $user['apellidos']); ?>
            </h2>

            <div class="datos-lista">
                <?php 
                $campos = [
                    'Correo' => $user['email'],
                    'Departamento' => $user['departamento'] ?? 'Huila',
                    'Ciudad' => $user['ciudad'] ?? 'Neiva',
                    'Dirección' => $user['direccion'] ?? 'No registrada',
                    'Número' => $user['telefono'] ?? '0000000000',
                    'Contraseña' => '********' 
                ];
                foreach ($campos as $label => $valor): 
                    $id_campo = strtolower(str_replace(['ó', 'ú', 'ñ'], ['o', 'u', 'n'], $label));
                ?>
                <div class="dato-item">
                    <label><?php echo $label; ?></label>
                    <div class="valor-caja">
                        <span class="valor-texto" id="txt-<?php echo $id_campo; ?>">
                            <?php echo htmlspecialchars($valor); ?>
                        </span>
                        <span class="edit-link" onclick="editarCampo('<?php echo $id_campo; ?>')" style="cursor:pointer">✏️</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>

<script>
function editarCampo(campo) {
    const span = document.getElementById('txt-' + campo);
    if (span.querySelector('input')) return;

    const valorActual = (campo === 'contrasena') ? '' : span.innerText.trim();
    const input = document.createElement('input');
    
    // Si el campo es contrasena, ocultamos los caracteres
    input.type = (campo === 'contrasena') ? 'password' : 'text';
    input.value = valorActual;
    input.placeholder = (campo === 'contrasena') ? 'Nueva contraseña' : '';
    input.className = 'input-inline';
    
    input.onkeydown = function(e) {
        if (e.key === 'Enter') guardarCambio(campo, input.value, span);
        if (e.key === 'Escape') span.innerHTML = (campo === 'contrasena') ? '********' : valorActual;
    };

    input.onblur = function() { guardarCambio(campo, input.value, span); };
    span.innerHTML = '';
    span.appendChild(input);
    input.focus();
}

function guardarCambio(campo, nuevoValor, span) {
    if (nuevoValor.trim() === "") {
        if (campo === 'contrasena') span.innerHTML = '********';
        return;
    }

    const datos = new FormData();
    datos.append('campo', campo);
    datos.append('valor', nuevoValor);

    fetch('actualizar_ajax.php', { method: 'POST', body: datos })
    .then(res => res.json())
    .then(data => { 
        if (data.status === 'success') {
            // Si es contraseña, volvemos a poner los asteriscos
            span.innerHTML = (campo === 'contrasena') ? '********' : nuevoValor;
        } 
    })
    .catch(err => console.error("Error:", err));
}
</script>
</body>
</html>