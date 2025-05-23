<?php
session_start();

// Verificar si el usuario está logueado y es un admin
if (!isset($_SESSION['id_usuari']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$dbname = "Muebles";
$username = "danielgil";
$password = "12345678";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];
    $categoria = $_POST['categoria'];

    try {
        $stmt = $pdo->prepare("INSERT INTO catalogo (nombre_producto, descripcion, precio, imagen_url, categoria) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $precio, $imagen, $categoria]);
        $success = "Producto añadido correctamente.";
    } catch (PDOException $e) {
        $error = "Error al añadir el producto: " . $e->getMessage();
    }
}

if (isset($_POST['delete_product'])) {
    $id_producto = $_POST['delete_product'];
    try {
        $stmt = $pdo->prepare("DELETE FROM catalogo WHERE id_producto = ?");
        $stmt->execute([$id_producto]);
        $success = "Producto eliminado correctamente.";
    } catch (PDOException $e) {
        $error = "Error al eliminar el producto: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $id_producto = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];
    $categoria = $_POST['categoria'];

    try {
        $stmt = $pdo->prepare("UPDATE catalogo SET nombre_producto = ?, descripcion = ?, precio = ?, imagen_url = ?, categoria = ? WHERE id_producto = ?");
        $stmt->execute([$nombre, $descripcion, $precio, $imagen, $categoria, $id_producto]);
        $success = "Producto actualizado correctamente.";
    } catch (PDOException $e) {
        $error = "Error al actualizar el producto: " . $e->getMessage();
    }
}

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

        /* Contenedor principal */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Contenedor de formularios */
        .form-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        
        .form-section {
            width: 48%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .form-section h2 {
            margin-top: 0;
        }
        
        @media (max-width: 768px) {
            .form-section {
                width: 100%;
            }
        }
        
        /* Estilos para los formularios */
        form div {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        textarea {
            height: 100px;
            resize: vertical;
        }
        
        button[type="submit"] {
            background-color: #1abc9c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        button[type="submit"]:hover {
            background-color: #16a085;
        }
        
        /* Estilos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f4f4f4;
        }
        
        tr:hover {
            background-color: #f9f9f9;
        }
        
        img {
            max-width: 100px;
            height: auto;
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

        .sidebar.open {
            left: 0;
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
            content: '\1F6D2';
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
        <h1>Dashboard Administrador - Productos</h1>
        <div style="position: absolute; top: 20px; right: 80px;">
            <a href="dashboard_clientes_admin.php" style="background-color: #1abc9c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-size: 16px;">Administrar Clientes</a>
        </div>
    </header>

    <div class="content">
        <div class="container">
            <div class="form-container">
                <div class="form-section">
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

                <div class="form-section">
                    <h2>Modificar Producto</h2>
                    <form method="POST" action="">
                        <div>
                            <label for="id_producto">Seleccionar producto:</label>
                            <select name="id_producto" id="id_producto" onchange="autofillProduct()">
                                <option value="">-- Selecciona un producto --</option>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?php echo $producto['id_producto']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                        data-descripcion="<?php echo htmlspecialchars($producto['descripcion']); ?>"
                                        data-precio="<?php echo $producto['precio']; ?>"
                                        data-imagen="<?php echo htmlspecialchars($producto['imagen_url']); ?>"
                                        data-categoria="<?php echo $producto['categoria']; ?>">
                                        <?php echo $producto['id_producto'] . " - " . $producto['nombre_producto']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="nombre">Nombre del producto:</label>
                            <input type="text" name="nombre" id="edit_nombre" required>
                        </div>
                        <div>
                            <label for="descripcion">Descripción:</label>
                            <textarea name="descripcion" id="edit_descripcion" required></textarea>
                        </div>
                        <div>
                            <label for="precio">Precio:</label>
                            <input type="number" name="precio" id="edit_precio" required>
                        </div>
                        <div>
                            <label for="imagen">URL de la imagen:</label>
                            <input type="text" name="imagen" id="edit_imagen" required>
                        </div>
                        <div>
                            <label for="categoria">Categoría:</label>
                            <select name="categoria" id="edit_categoria" required>
                                <option value="mesas">Mesas</option>
                                <option value="sillas">Sillas</option>
                                <option value="sofas">Sofas</option>
                                <option value="dormitorio">Dormitorio</option>
                                <option value="cocina">Cocina</option>
                                <option value="decoracion">Decoracion</option>
                            </select>
                        </div>
                        <button type="submit" name="update_product">Actualizar Producto</button>
                    </form>
                </div>
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
    </div>

    <script>
        function autofillProduct() {
            const select = document.getElementById('id_producto');
            const selected = select.options[select.selectedIndex];

            document.getElementById('edit_nombre').value = selected.getAttribute('data-nombre') || '';
            document.getElementById('edit_descripcion').value = selected.getAttribute('data-descripcion') || '';
            document.getElementById('edit_precio').value = selected.getAttribute('data-precio') || '';
            document.getElementById('edit_imagen').value = selected.getAttribute('data-imagen') || '';
            document.getElementById('edit_categoria').value = selected.getAttribute('data-categoria') || '';
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('open');
            
            if (sidebar.classList.contains('open')) {
                sidebar.style.left = '0';
                content.style.marginLeft = '250px';
            } else {
                sidebar.style.left = '-250px';
                content.style.marginLeft = '0';
            }
        }

        function toggleDropdown(event) {
            event.preventDefault();
            const dropdown = event.target.nextElementSibling;
            dropdown.classList.toggle('show');
        }

        function toggleCart() {
            const cart = document.getElementById('cartSidebar');
            cart.classList.toggle('open');
        }
    </script>
</body>
</html>

