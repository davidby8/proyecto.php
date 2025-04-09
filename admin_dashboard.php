<?php
session_start();

// Verificar si el usuario está logueado y es un admin
if (!isset($_SESSION['id_usuari']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php"); // Redirigir si no es admin
    exit();
}

$host = "localhost"; 
$dbname = "Muebles";
$username = "danielgil"; 
$password = "12345678"; 

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Añadir un producto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];
    $categoria = $_POST['categoria']; // Obtener la categoría seleccionada

    try {
        // Cambiar nombres de columnas para que coincidan con la BD
        $stmt = $pdo->prepare("INSERT INTO catalogo (nombre_producto, descripcion, precio, imagen_url, categoria) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $precio, $imagen, $categoria]); // Incluir la categoría
        $success = "Producto añadido correctamente."; // Mensaje de éxito
    } catch (PDOException $e) {
        $error = "Error al añadir el producto: " . $e->getMessage();
    }
}

// Eliminar un producto
if (isset($_POST['delete_product'])) {
    $id_producto = $_POST['delete_product'];

    // Eliminar el producto de la base de datos
    try {
        $stmt = $pdo->prepare("DELETE FROM catalogo WHERE id_producto = ?");
        $stmt->execute([$id_producto]);
        $success = "Producto eliminado correctamente.";
    } catch (PDOException $e) {
        $error = "Error al eliminar el producto: " . $e->getMessage();
    }
}

// Obtener todos los productos
try {
    $stmt = $pdo->prepare("SELECT * FROM catalogo");
    $stmt->execute();
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error al obtener los productos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Muebles</title>
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

</div>

<button class="cart-btn" onclick="toggleCart()"></button>

<div id="cartSidebar" class="cart-sidebar">
  <span class="close-btn" onclick="toggleCart()">&times;</span>
  <h3>Carrito</h3>
  <div class="cart-items">
    <?php
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
 
    <header>
        <h1>Dashboard Administrador</h1>
    </header>

    <div class="content">
        <div class="container">
            <h2>Añadir Producto</h2>
            <form method="POST" action="">
                <div>
                    <label for="nombre">Nombre del producto:</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>
                <div>
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" required></textarea>
                </div>
                <div>
                    <label for="precio">Precio:</label>
                    <input type="number" name="precio" id="precio" required>
                </div>
                <div>
                    <label for="imagen">URL de la imagen:</label>
                    <input type="text" name="imagen" id="imagen" required>
                </div>
                <div>
                    <label for="categoria">Categoría:</label>
                    <select name="categoria" id="categoria" required>
                        <option value="mesas">Mesas</option>
                        <option value="sillas">Sillas</option>
                        <option value="sofas">Sofas</option>
                        <option value="dormitorio">Dormitorio</option>
                        <option value="cocina">Cocina</option>
                        <option value="decoracion">Decoracion</option>
                    </select>
                </div>
                <button type="submit" name="add_product">Añadir Producto</button>
            </form>
        </div>

        <h3>Lista de Productos</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['id_producto']; ?></td>
                        <td><?php echo $producto['nombre_producto']; ?></td>
                        <td><?php echo $producto['descripcion']; ?></td>
                        <td><?php echo $producto['precio']; ?> €</td>
                        <td><img src="<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre_producto']; ?>" width="50"></td>
                        <td><?php echo $producto['categoria']; ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="delete_product" value="<?php echo $producto['id_producto']; ?>">
                                <button type="submit" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Función para abrir y cerrar la barra lateral
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('open');
            
            if (sidebar.classList.contains('open')) {
                sidebar.style.left = '0'; // Mostrar barra lateral
                content.style.marginLeft = '250px'; // Mover el contenido a la derecha
            } else {
                sidebar.style.left = '-250px'; // Ocultar barra lateral
                content.style.marginLeft = '0'; // Volver el contenido a su lugar
            }
        }
    </script>
</body>
</html>

