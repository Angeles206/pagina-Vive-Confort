<?php
session_start();
// Lógica para detectar si el usuario de Neiva ha iniciado sesión
$sesion_activa = isset($_SESSION['correo']) || isset($_SESSION['email']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros | ViveConfort</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css"> <link rel="stylesheet" href="nosotros.css"> </head>
<body>

    <div class="about-container">
        <header class="about-header">
            <h1>ViveConfort</h1>
        </header>

        <section class="about-content">
            <p>
                Bienvenidos a <strong>ViveConfort</strong>, tu tienda virtual de confianza nacida en el corazón del 
                Huila. Nuestra pasión es combinar la tecnología con el cuidado personal para ofrecerte 
                una experiencia de belleza única desde la comodidad de tu hogar.
            </p>

            <div class="vision-box">
                <h3>Nuestra Visión</h3>
                <p>
                    Nos enfocamos en transformar la vida de las personas a través del desarrollo de herramientas 
                    digitales útiles, garantizando siempre la calidad en nuestros productos de maquillaje y skincare.
                </p>
            </div>

            <p>
                Cada producto en nuestro catálogo ha sido seleccionado pensando en la estabilidad y el bienestar 
                de nuestra comunidad. Queremos que te sientas segura, cómoda y radiante.
            </p>
        </section>

        <section class="social-section">
            <h3 style="font-family: 'Poppins', sans-serif; color: #ad1457;">¡Conéctate con nosotros!</h3>
            <div class="social-icons">
                <a href="https://whatsapp.com/channel/0029Vb76mWLIXnlpcfRcUE0U" target="_blank" title="WhatsApp">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="https://www.instagram.com/vive_confort?igsh=MWt5NDM3ODFnYmxhYg==" target="_blank" title="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="https://www.tiktok.com/@viveconfort?_r=1&_t=ZS-91EPaRKaknn" target="_blank" title="TikTok">
                    <i class="fa-brands fa-tiktok"></i>
                </a>
            </div>
        </section>

        <div>
            <a href="Inicio.php" class="btn-volver">Volver al Inicio</a>
        </div>
    </div>

</body>
</html>