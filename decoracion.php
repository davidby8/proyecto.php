<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuari'])) {
    header("Location: login.php"); // Redirigir al login si no está logueado
    exit();
}

// Inicializar el carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Conexión a la base de datos
$host = "localhost"; 
$dbname = "Muebles";
$username = "danielgil"; 
$password = "12345678";

try {
    // Establecer conexión con la base de datos utilizando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Establecer el modo de error de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Procesar agregar al carrito
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    
    try {
        // Obtener información del producto
        $stmt = $pdo->prepare("SELECT * FROM catalogo WHERE id_producto = :id");
        $stmt->bindParam(':id', $product_id);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            // Verificar si el producto ya está en el carrito
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $product_id) {
                    $item['quantity'] += 1;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                // Agregar nuevo producto al carrito
                $_SESSION['cart'][] = array(
                    'id' => $product['id_producto'],
                    'name' => $product['nombre_producto'],
                    'price' => $product['precio'],
                    'quantity' => 1,
                    'image' => $product['imagen_url']
                );
            }
            
            // Redirigir para evitar reenvío del formulario
            header("Location: decoracion.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Error al obtener el producto: " . $e->getMessage());
    }
}

try {
    // Definir la categoría como 'Decoracion'
    $categoria = 'Decoracion';
    
    // Recuperar los productos que sean de la categoría 'Decoracion'
    $stmt = $pdo->prepare("SELECT * FROM catalogo WHERE categoria = :categoria");
    $stmt->bindParam(':categoria', $categoria);
    $stmt->execute();
    
    // Obtener los productos como un array asociativo
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener los productos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catálogo de Decoración - Decoración</title>
  <style>
    /* Estilos similares a cocina.php */
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .sidebar {
      width: 250px;
      position: fixed;
      top: 0;
      left: -250px;
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

    .sidebar.open {
      left: 0;
    }

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
      content: '\1F6D2';
      font-size: 2rem;
    }

    .cart-sidebar {
      width: 350px;
      position: fixed;
      top: 0;
      right: -350px;
      height: 100%;
      background-color: #34495e;
      padding-top: 20px;
      transition: 0.3s;
      z-index: 2;
      overflow-y: auto;
    }

    .cart-sidebar.open {
      right: 0;
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
      padding: 10px 20px;
      color: white;
    }

    .cart-item {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #2c3e50;
    }

    .cart-item img {
      width: 60px;
      height: 60px;
      border-radius: 4px;
      margin-right: 15px;
    }

    .cart-item-info {
      flex-grow: 1;
    }

    .cart-item-name {
      font-weight: bold;
      margin-bottom: 5px;
    }

    .cart-item-price {
      color: #e67e22;
    }

    .cart-item-quantity {
      display: flex;
      align-items: center;
      margin-top: 5px;
    }

    .cart-item-quantity button {
      background-color: #2c3e50;
      color: white;
      border: none;
      width: 25px;
      height: 25px;
      border-radius: 50%;
      cursor: pointer;
    }

    .cart-item-quantity span {
      margin: 0 10px;
    }

    .cart-total {
      padding: 20px;
      font-size: 1.2rem;
      font-weight: bold;
      text-align: right;
      border-top: 1px solid #2c3e50;
      margin-top: 10px;
    }

    .checkout-btn {
      display: block;
      background-color: #e67e22;
      color: white;
      text-align: center;
      padding: 15px;
      margin: 20px;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.3s;
    }

    .checkout-btn:hover {
      background-color: #d35400;
    }

    .empty-cart {
      text-align: center;
      padding: 20px;
    }
  </style>
</head>
<body>

  <button class="menu-btn" onclick="toggleSidebar()">☰</button>

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
        <a href="cocina.php">Cocina</a>
      </div>
    </div>
    <a href="decoracion.php">Decoración</a>
    <a href="#">Últimas unidades</a>
    <a href="logout.php" class="logout-btn">Cerrar sesión</a>
  </div>

  <button class="cart-btn" onclick="toggleCart()"></button>

  <div id="cartSidebar" class="cart-sidebar">
    <span class="close-btn" onclick="toggleCart()">&times;</span>
    <h3 style="color: white; padding: 0 20px;">Carrito de compras</h3>
    <div class="cart-items">
      <?php if (!empty($_SESSION['cart'])): ?>
        <?php 
          $total = 0;
          foreach ($_SESSION['cart'] as $item): 
          $subtotal = $item['price'] * $item['quantity'];
          $total += $subtotal;
        ?>
          <div class="cart-item">
            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
            <div class="cart-item-info">
              <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
              <div class="cart-item-price">$<?php echo number_format($item['price'], 2); ?></div>
              <div class="cart-item-quantity">
                <button onclick="updateQuantity(<?php echo $item['id']; ?>, -1)">-</button>
                <span><?php echo $item['quantity']; ?></span>
                <button onclick="updateQuantity(<?php echo $item['id']; ?>, 1)">+</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="cart-total">
          Total: $<?php echo number_format($total, 2); ?>
        </div>
        <a href="carrito.php" class="checkout-btn">Proceder al pago</a>
      <?php else: ?>
        <div class="empty-cart">
          <p>Tu carrito está vacío</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <header>
    <h1>Catálogo de Decoración</h1>
  </header>

  <div class="content">
    <div class="container">
      <h2>Catálogo de Decoración</h2>
      <div class="product-container">
        <?php if (!empty($productos)): ?>
          <?php foreach ($productos as $producto): ?>
            <div class="product-card">
              <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
              <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
              <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
              <p class="price">$<?php echo htmlspecialchars($producto['precio']); ?></p>
              <a href="decoracion.php?add_to_cart=<?php echo $producto['id_producto']; ?>" class="btn">Agregar al carrito</a>
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
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const content = document.querySelector('.content');
      sidebar.classList.toggle('open');
      
      if (sidebar.classList.contains('open')) {
        content.style.marginLeft = '250px';
      } else {
        content.style.marginLeft = '0';
      }
    }

    function toggleDropdown(event) {
      event.stopPropagation();
      document.getElementById("catalogDropdown").classList.toggle("show");
    }

    function toggleCart() {
      const cartSidebar = document.getElementById('cartSidebar');
      cartSidebar.classList.toggle('open');
    }

    function updateQuantity(productId, change) {
      // Enviar una solicitud al servidor para actualizar la cantidad
      window.location.href = `update_cart.php?id=${productId}&change=${change}`;
    }

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