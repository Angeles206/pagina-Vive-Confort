<?php
session_start();
$sesion_activa = isset($_SESSION['correo']) || isset($_SESSION['email']);

$menu_items = [];

// 1. Botón de inicio de sesión (Solo si NO ha entrado)
if (!$sesion_activa) {
    $menu_items["Iniciar sesión"] = "http://localhost/viveconfort/inicioS/login.php";
}

// 2. Secciones que SIEMPRE aparecen
$menu_items["Productos"] = "http://localhost/viveconfort/Productos/productos.php";
$menu_items["Sobre nosotros"] = "nosotros.php";

// 3. Secciones especiales (Solo si YA inició sesión)
if ($sesion_activa) {
    $menu_items["Favoritos"] = "http://localhost/viveconfort/inicioS/favoritos.php";
    $menu_items["Mis Pedidos"] = "http://localhost/viveconfort/Carrito/carrito.php"; 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViveConfort | Tienda de Belleza</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="main-header">
    <div class="top-bar">
        <div class="logo">
            <img src="imagenes/ViveConfort.png" alt="ViveConfort Logo" class="logo-img">
        </div>
        <div class="header-icons">
            <a href="http://localhost/viveconfort/inicioS/perfil.php" class="icon-link">
                <img src="imagenes/persona.png" alt="Cuenta" class="header-icon-img">
            </a>    
            <span class="icon">❔</span>
        </div>
    </div>

    <nav class="nav-bar">
        <?php foreach ($menu_items as $texto => $enlace): ?>
            <a href="<?php echo $enlace; ?>" class="nav-btn"><?php echo $texto; ?></a>
        <?php endforeach; ?>
    </nav>
</header>

<main>
    <section class="banner-title">
        <h1>
            <?php echo ($sesion_activa && isset($_SESSION['nombres'])) ? "Bienvenida, " . $_SESSION['nombres'] : "ViveConfort"; ?>
        </h1>
    </section>

    <div class="carousel-wrapper">
        <button class="arrow left-arrow" onclick="moveSlide(-1)">&#10094;</button>
        
        <div class="carousel-container">
            <div class="carousel-track" id="track">
                <img src="imagenes/lip ol.jpeg" alt="Producto 1">
                <img src="imagenes/labiales3.jpeg" alt="Producto 2">
                <img src="imagenes/polvos.jpeg" alt="Producto 3">
                <img src="imagenes/sombras.jpeg" alt="Producto 4">
            </div>
        </div>

        <button class="arrow right-arrow" onclick="moveSlide(1)">&#10095;</button>
    </div>
</main>

<script>
    let currentIndex = 0;
    function moveSlide(step) {
        const track = document.getElementById('track');
        const slides = document.querySelectorAll('.carousel-track img');
        const totalSlides = slides.length;
        
        if (totalSlides === 0) return;

        currentIndex += step;
        
        if (currentIndex >= totalSlides) currentIndex = 0;
        if (currentIndex < 0) currentIndex = totalSlides - 1;
        
        track.style.transform = `translateX(${currentIndex * -100}%)`;
    }
</script>

</body>
</html>