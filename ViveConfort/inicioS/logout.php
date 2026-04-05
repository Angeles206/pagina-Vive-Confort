<?php
session_start();
session_unset();
session_destroy();

// Al estar en la misma carpeta 'inicioS', redirige directo a login.php
header("Location: login.php");
exit;
?>