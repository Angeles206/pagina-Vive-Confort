<?php
session_start();
header('Content-Type: application/json');

// 1. Verificar sesión antes de conectar
if (!isset($_SESSION['correo'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesion expirada']);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "viveconfort");

if ($conexion->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Fallo de conexion']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $campo = $_POST['campo'] ?? '';
    $valor = trim($_POST['valor'] ?? '');
    $email_actual = $_SESSION['correo'];

    $mapa = [
        'correo'       => 'email',
        'departamento' => 'departamento',
        'ciudad'       => 'ciudad',
        'direccion'    => 'direccion',
        'numero'       => 'telefono',
        'contrasena'   => 'contraseña' // Agregamos el mapeo para la contraseña
    ];

    if (isset($mapa[$campo])) {
        $columna = $mapa[$campo];
        
        // Evitar que guarden valores vacíos
        if (empty($valor)) {
            echo json_encode(['status' => 'error', 'message' => 'El valor no puede estar vacio']);
            exit;
        }

        // --- LÓGICA PARA CONTRASEÑA ---
        // Si el campo es contrasena, la encriptamos antes de guardar
        if ($campo === 'contrasena') {
            $valor = $valor;
        }

        $stmt = $conexion->prepare("UPDATE usuarios SET $columna = ? WHERE email = ?");
        $stmt->bind_param("ss", $valor, $email_actual);
        
        if ($stmt->execute()) {
            // Si cambió el correo, actualizamos la llave maestra de la sesión
            if ($columna === 'email') {
                $_SESSION['correo'] = $valor;
            }
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conexion->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Campo no valido']);
    }
}
$conexion->close();
?>