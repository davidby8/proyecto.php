<?php 
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuari'])) {
    header("Location: login.php"); // Redirigir al login si no está logueado
    exit();
}

// Incluir archivo de configuración para la conexión a la base de datos
require 'config.php';

try {
    // Recuperar los datos del usuario desde la base de datos
    $stmt = $pdo->prepare("SELECT nom_usuari, cognom_usuari FROM usuaris WHERE id_usuari = :id_usuari");
    $stmt->bindParam(':id_usuari', $_SESSION['id_usuari']);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener la información del usuario: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Usuario - Muebles</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    /* Barra lateral */
    .sidebar {
      width: 250px;
      position: fixed;
      top: 0;
      left: -250px; /* Inicialmente oculta */
      height: 100%;
      background-color: #34495e;
      padding-top: 20px;
      transition: 0.3s;
      z-index: 2;
    }

    .sidebar a {
      color: white;
      padding: 15px 20px;
      text-decoration: none;
      font-size: 1.2rem;
      display: block;
      transition: background-color 0.3s;
    }

    .sidebar a:hover {
      background-color: #1abc9c;
    }

    .sidebar .close-btn {
      font-size: 36px;
      position: absolute;
      top: 10px;
      right: 20px;
      cursor: pointer;
    }

    /* Estilo para el encabezado */
    header {
      background-color: #2c3e50;
      color: #fff;
      padding: 20px 0;
      text-align: center;
      width: 100%;
      box-sizing: border-box;
      position: relative;
      z-index: 1;
    }

    header h1 {
      font-size: 2.5rem;
      margin: 0;
    }

    /* Estilo para el contenido */
    .container {
      max-width: 1200px;
      margin: 30px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .content h2 {
      font-size: 2rem;
      color: #2c3e50;
      margin-bottom: 10px;
    }

    .content p {
      font-size: 1.2rem;
      color: #34495e;
      margin: 20px 0;
    }

    .logout-btn {
      display: inline-block;
      padding: 12px 25px;
      background-color: #e67e22;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      font-size: 1rem;
      transition: background-color 0.3s ease;
      margin-top: 20px;
    }

    .logout-btn:hover {
      background-color: #d35400;
    }

    .footer {
      background-color: #2c3e50;
      color: #fff;
      text-align: center;
      padding: 20px;
      margin-top: 40px;
    }

    .footer p {
      margin: 0;
      font-size: 0.9rem;
    }

    /* Estilo para la parte superior con el título "Muebles" */
    .header-banner {
      background-color: #2c3e50;
      color: #fff;
      padding: 15px;
      text-align: center;
      font-size: 2rem;
      margin-bottom: 20px;
      position: relative;
    }

    /* Menú lateral */
    .menu-btn {
      font-size: 24px;
      cursor: pointer;
      color: #fff;
      background-color: transparent;
      border: none;
      padding: 15px;
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 2;
    }

    .content {
      padding: 20px;
      flex-grow: 1;
      transition: margin-left 0.3s;
    }

    /* Agregar animación al contenido cuando el menú se despliega */
    .sidebar.open {
      left: 0; /* Mueve la barra lateral a la vista */
    }

    /* Botón del carrito en la parte superior derecha */
    .cart-btn {
      font-size: 1.8rem;
      color: white;
      background-color: transparent;
      border: none;
      cursor: pointer;
      position: absolute;
      top: 20px;
      right: 20px;
      z-index: 3;
    }

    /* Estilo para el icono del carrito */
    .cart-btn:before {
      content: '\1F6D2'; /* Carrito de compras */
      font-size: 2rem;
    }

    /* Carrito desplegable */
    .cart-sidebar {
      width: 250px;
      position: fixed;
      top: 0;
      right: -250px;
      height: 100%;
      background-color: #34495e;
      padding-top: 20px;
      transition: 0.3s;
      z-index: 2;
      overflow-y: auto;
    }

    .cart-sidebar.open {
      right: 0; /* Mueve el carrito a la vista */
    }

    .cart-sidebar a {
      color: white;
      padding: 15px 20px;
      text-decoration: none;
      font-size: 1.2rem;
      display: block;
      transition: background-color 0.3s;
    }

    .cart-sidebar a:hover {
      background-color: #1abc9c;
    }

    .cart-sidebar .close-btn {
      font-size: 36px;
      position: absolute;
      top: 10px;
      left: 20px;
      cursor: pointer;
    }

    .cart-items {
      padding: 10px 0;
      color: white;
    }

    .cart-items p {
      font-size: 1rem;
      margin: 5px 20px;
    }
  </style>
</head>
<body>

  <!-- Botón para abrir la barra lateral -->
  <button class="menu-btn" onclick="toggleSidebar()">☰</button>

  <!-- Barra lateral -->
  <div id="sidebar" class="sidebar">
    <span class="close-btn" onclick="toggleSidebar()">&times;</span>
    <a href="#">Nuevos productos</a>
    <a href="catalogo.php">Catálogo</a> <!-- Redirige a catalogo.php -->
    <a href="#">Últimas unidades</a>
    <a href="logout.php" class="logout-btn">Cerrar sesión</a>
  </div>

  <!-- Botón del carrito (en la parte superior derecha) -->
  <button class="cart-btn" onclick="toggleCart()"></button>

  <!-- Carrito desplegable -->
  <div id="cartSidebar" class="cart-sidebar">
    <span class="close-btn" onclick="toggleCart()">&times;</span>
    <h3>Carrito</h3>
    <div class="cart-items">
      <?php
        // Mostrar los productos del carrito
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            foreach ($_SESSION['cart'] as $item) {
                echo '<p>' . htmlspecialchars($item['name']) . ' - $' . htmlspecialchars($item['price']) . '</p>';
            }
        } else {
            echo '<p>No hay productos en el carrito.</p>';
        }
      ?>
    </div>
  </div>

  <!-- Banner superior con el título "Muebles" -->
  <div class="header-banner">
    <h1>Muebles</h1>
  </div>

  <!-- Contenido principal -->
  <div class="content">
    <div class="container">
      <h2>¡Has iniciado sesión correctamente!</h2>
      <p>Ahora puedes acceder al catálogo de muebles, hacer pedidos y gestionar tu perfil.</p>
      <a href="logout.php" class="logout-btn">Cerrar sesión</a>
    </div>
  </div>

  <footer>
    <p>© 2025 Muebles. Todos los derechos reservados.</p>
  </footer>

  <script>
    // Función para abrir y cerrar la barra lateral
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const content = document.querySelector('.content');
      sidebar.classList.toggle('open');
      
      // Ajustar el margen del contenido dependiendo de si la barra lateral está abierta o cerrada
      if (sidebar.classList.contains('open')) {
        content.style.marginLeft = '250px'; // Desplazar el contenido a la derecha cuando la barra lateral esté abierta
      } else {
        content.style.marginLeft = '0'; // Devolver el contenido a su lugar original cuando la barra lateral esté cerrada
      }
    }

    // Función para abrir y cerrar el carrito
    function toggleCart() {
      const cartSidebar = document.getElementById('cartSidebar');
      cartSidebar.classList.toggle('open');
    }
  </script>

</body>
</html>




