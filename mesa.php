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
    // Recuperar los productos que sean de la categoría 'Mesas'
    $stmt = $pdo->prepare("SELECT * FROM catalogo WHERE categoria = :categoria");
    $stmt->bindParam(':categoria', $categoria);
    $categoria = 'Mesas';  // Definir la categoría como 'Mesas' con la "M" mayúscula
    $stmt->execute();
    
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$productos) {
        echo "No se encontraron productos en la categoría Mesas.";  // Mensaje si no hay productos
    }
} catch (PDOException $e) {
    die("Error al obtener los productos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catálogo de Mesas - Muebles</title>
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

    .product-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-evenly;
      margin: 20px;
    }

    .product-card {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      margin: 10px;
      padding: 20px;
      width: 280px;
      text-align: center;
    }

    .product-card img {
      width: 100%;
      height: auto;
      border-radius: 8px;
    }

    .product-card h3 {
      color: #2c3e50;
      font-size: 1.5rem;
      margin: 15px 0;
    }

    .product-card p {
      color: #34495e;
      font-size: 1rem;
      margin-bottom: 10px;
    }

    .product-card .price {
      font-size: 1.2rem;
      font-weight: bold;
      color: #e67e22;
    }

    .product-card .btn {
      background-color: #e67e22;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.3s;
    }

    .product-card .btn:hover {
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

    /* Estilos para el menú desplegable del catálogo */
    .dropdown {
      position: relative;
      display: block;
    }

    .dropbtn {
      color: white;
      padding: 15px 20px;
      text-decoration: none;
      font-size: 1.2rem;
      display: block;
      transition: background-color 0.3s;
      background-color: transparent;
      border: none;
      width: 100%;
      text-align: left;
      cursor: pointer;
    }

    .dropbtn:hover {
      background-color: #1abc9c;
    }

    .dropdown-content {
      display: none;
      background-color: #2c3e50;
      width: 100%;
    }

    .dropdown-content a {
      color: white;
      padding: 12px 30px;
      text-decoration: none;
      display: block;
      font-size: 1.1rem;
    }

    .dropdown-content a:hover {
      background-color: #1abc9c;
    }

    .show {
      display: block;
    }

    /* Carrito y barra de menú del carrito */
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
    <div class="dropdown">
      <button class="dropbtn" onclick="toggleDropdown(event)">Catálogo</button>
      <div id="catalogDropdown" class="dropdown-content">
        <a href="mesa.php">Mesas</a>
        <a href="silla.php">Sillas</a>
        <a href="sofa.php">Sofás</a>
        <a href="dormitorio.php">Dormitorio</a>
        <a href="cocina.php">Cocina</a> <!-- Aquí está el enlace a cocina.php -->
      </div>
    </div>
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
            echo "<p>{$item['name']} - {$item['quantity']} x \$" . number_format($item['price'], 2) . "</p>";
          }
        } else {
          echo "<p>Tu carrito está vacío.</p>";
        }
      ?>
    </div>
    <a href="carrito.php">Ver carrito</a>
  </div>

  <!-- Banner superior con el título "Catálogo de Mesas" -->
  <header>
    <h1>Catálogo de Mesas</h1>
  </header>

  <!-- Contenido principal -->
  <div class="content">
    <div class="container">
      <h2>Catálogo de Mesas</h2>
      <div class="product-container">
        <?php if (!empty($productos)): ?>
          <?php foreach ($productos as $producto): ?>
            <div class="product-card">
              <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
              <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
              <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
              <p class="price">$<?php echo htmlspecialchars($producto['precio']); ?></p>
              <a href="agregar_carrito.php?id=<?php echo $producto['id_producto']; ?>" class="btn">Agregar al carrito</a>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No hay productos disponibles en esta categoría.</p>
        <?php endif; ?>
      </div>
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

    // Función para mostrar/ocultar el menú desplegable del catálogo
    function toggleDropdown(event) {
      event.stopPropagation(); // Evita que el evento se propague y cierre el menú inmediatamente
      document.getElementById("catalogDropdown").classList.toggle("show");
    }

    // Función para abrir/cerrar el carrito
    function toggleCart() {
      const cartSidebar = document.getElementById('cartSidebar');
      cartSidebar.classList.toggle('open');
    }

    // Cerrar el menú desplegable si se hace clic fuera de él
    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }
  </script>

</body>
</html>

