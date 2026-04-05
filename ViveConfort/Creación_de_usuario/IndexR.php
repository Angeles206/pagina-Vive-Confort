<?php
session_start(); 

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin_global' && $_SESSION['rol'] !== 'admin_local')) {    // Salimos de 'Creación_de_usuario' e entramos a 'inicioS'
    header("Location: ../inicioS/login.php");
    exit();
} 

// 3. Si pasó el candado, ahora sí conectamos a la base de datos
$host = "localhost"; $usuario = "root"; $clave = ""; $bd = "viveconfort";
$conexion = new mysqli($host, $usuario, $clave, $bd);

if ($conexion->connect_error) { die("Error: " . $conexion->connect_error); }

$sql = "SELECT * FROM usuarios";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Gestión de Usuarios - Vive Confort</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div id="contenedor">
    <h1>Gestión de Usuarios</h1>
    
    <div class="botones-centro">
        <a href="registro_usuario.php" class="btn-principal">➕ Registrar Nuevo Usuario</a>
    </div>

    <input type="text" id="busqueda" class="input-busqueda" placeholder="🔍 Buscar por nombre, email o ciudad...">

    <div class="tabla-container">
        <table>
            <thead>
                <tr>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>País</th>
                    <th>Departamento</th>
                    <th>Ciudad</th>
                    <th>C. Postal</th>
                    <th>Dirección</th>
                    <th>Email</th>
                    <th>Teléfono</th> 
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody id="tabla-usuarios">
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while($row = $resultado->fetch_assoc()) {
                        $emailUrl = urlencode($row['email']);
                        echo "<tr>
                                <td>" . htmlspecialchars($row['nombres']) . "</td>
                                <td>" . htmlspecialchars($row['apellidos']) . "</td>
                                <td>" . htmlspecialchars($row['pais']) . "</td>
                                <td>" . htmlspecialchars($row['departamento']) . "</td>
                                <td>" . htmlspecialchars($row['ciudad']) . "</td>
                                <td>" . htmlspecialchars($row['codigoPostal']) . "</td>
                                <td>" . htmlspecialchars($row['direccion']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['telefono']) . "</td>
                                <td>" . htmlspecialchars($row['rol']) . "</td>
                                <td>
                                    <a href='editar_usuario.php?email=$emailUrl' class='btn-edit'>Editar</a>
                                    <a href='eliminar_usuario.php?email=$emailUrl' class='btn-delete'>Eliminar</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center;'>No hay usuarios registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
         <div class="footer-acciones">
                <a href="../Productos/productos.php" class="btn-volver">Volver a la tienda</a>
                <a href="../inicioS/perfil.php" class="btn-perfil">Volver al perfil</a>
       </div>
</div>
</div>

<script>
    document.getElementById('busqueda').addEventListener('keyup', function() {
        let filtro = this.value.toLowerCase();
        let filas = document.querySelectorAll('#tabla-usuarios tr');

        filas.forEach(fila => {
            let textoFila = fila.innerText.toLowerCase();
            if (textoFila.includes(filtro)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>