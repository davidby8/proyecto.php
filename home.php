<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Tienda de Muebles</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Bienvenido a nuestra Tienda de Muebles</h1>
            <p>Explora lo mejor en muebles para tu hogar</p>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
            <p>Nos alegra tenerte con nosotros.</p>
        </div>
    </section>

    <section class="about">
        <div class="container">
            <h2>Sobre Nosotros</h2>
            <p>En <strong>Muebles</strong>, ofrecemos productos de alta calidad para embellecer tu hogar con los mejores muebles del mercado.</p>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2023 Muebles. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
