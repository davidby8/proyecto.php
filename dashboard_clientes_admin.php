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

// Añadir nuevo cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_cliente'])) {
    $username = $_POST['username'];
    $contrasenya = password_hash($_POST['contrasenya'], PASSWORD_DEFAULT);
    $nom_usuari = $_POST['nom_usuari'];
    $cognom_usuari = $_POST['cognom_usuari'];
    $correu_electronic = $_POST['correu_electronic'];
    $rol = 'user'; // Rol por defecto

    try {
        $stmt = $pdo->prepare("INSERT INTO usuaris (username, contrasenya, nom_usuari, cognom_usuari, correu_electronic, rol) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $contrasenya, $nom_usuari, $cognom_usuari, $correu_electronic, $rol]);
        $success = "Cliente añadido correctamente.";
    } catch (PDOException $e) {
        $error = "Error al añadir el cliente: " . $e->getMessage();
    }
}

// Eliminar cliente
if (isset($_POST['delete_cliente'])) {
    $id_usuari = $_POST['delete_cliente'];
    try {
        $stmt = $pdo->prepare("DELETE FROM usuaris WHERE id_usuari = ?");
        $stmt->execute([$id_usuari]);
        $success = "Cliente eliminado correctamente.";
    } catch (PDOException $e) {
        $error = "Error al eliminar el cliente: " . $e->getMessage();
    }
}

// Modificar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cliente'])) {
    $id_usuari = $_POST['id_usuari'];
    $username = $_POST['username'];
    $nom_usuari = $_POST['nom_usuari'];
    $cognom_usuari = $_POST['cognom_usuari'];
    $correu_electronic = $_POST['correu_electronic'];
    
    // Solo actualizar contraseña si se proporcionó una nueva
    $update_password = '';
    if (!empty($_POST['contrasenya'])) {
        $contrasenya = password_hash($_POST['contrasenya'], PASSWORD_DEFAULT);
        $update_password = "contrasenya = ?, ";
    }

    try {
        if (!empty($update_password)) {
            $stmt = $pdo->prepare("UPDATE usuaris SET username = ?, $update_password nom_usuari = ?, cognom_usuari = ?, correu_electronic = ? WHERE id_usuari = ?");
            $stmt->execute([$username, $contrasenya, $nom_usuari, $cognom_usuari, $correu_electronic, $id_usuari]);
        } else {
            $stmt = $pdo->prepare("UPDATE usuaris SET username = ?, nom_usuari = ?, cognom_usuari = ?, correu_electronic = ? WHERE id_usuari = ?");
            $stmt->execute([$username, $nom_usuari, $cognom_usuari, $correu_electronic, $id_usuari]);
        }
        $success = "Cliente actualizado correctamente.";
    } catch (PDOException $e) {
        $error = "Error al actualizar el cliente: " . $e->getMessage();
    }
}

// Obtener lista de clientes (excluyendo al admin actual)
try {
    $stmt = $pdo->prepare("SELECT * FROM usuaris WHERE id_usuari != ?");
    $stmt->execute([$_SESSION['id_usuari']]);
    $clientes = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error al obtener los clientes: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Clientes</title>
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
        input[type="password"],
        input[type="email"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
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
        <h1>Dashboard Administrador - Clientes</h1>
        <div style="position: absolute; top: 20px; right: 80px;">
            <a href="admin_dashboard.php" style="background-color: #1abc9c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-size: 16px;">Administrar Productos</a>
        </div>
    </header>

    <div class="content">
        <div class="container">
            <?php if (isset($success)): ?>
                <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <div class="form-section">
                    <h2>Añadir Cliente</h2>
                    <form method="POST" action="">
                        <div>
                            <label for="username">Nombre de usuario:</label>
                            <input type="text" name="username" id="username" required>
                        </div>
                        <div>
                            <label for="contrasenya">Contraseña:</label>
                            <input type="password" name="contrasenya" id="contrasenya" required>
                        </div>
                        <div>
                            <label for="nom_usuari">Nombre:</label>
                            <input type="text" name="nom_usuari" id="nom_usuari" required>
                        </div>
                        <div>
                            <label for="cognom_usuari">Apellido:</label>
                            <input type="text" name="cognom_usuari" id="cognom_usuari" required>
                        </div>
                        <div>
                            <label for="correu_electronic">Correo Electrónico:</label>
                            <input type="email" name="correu_electronic" id="correu_electronic" required>
                        </div>
                        <button type="submit" name="add_cliente">Añadir Cliente</button>
                    </form>
                </div>

                <div class="form-section">
                    <h2>Modificar Cliente</h2>
                    <form method="POST" action="">
                        <div>
                            <label for="id_usuari">Seleccionar cliente:</label>
                            <select name="id_usuari" id="id_usuari" onchange="autofillCliente()">
                                <option value="">-- Selecciona un cliente --</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?php echo $cliente['id_usuari']; ?>"
                                        data-username="<?php echo htmlspecialchars($cliente['username']); ?>"
                                        data-nom_usuari="<?php echo htmlspecialchars($cliente['nom_usuari']); ?>"
                                        data-cognom_usuari="<?php echo htmlspecialchars($cliente['cognom_usuari']); ?>"
                                        data-correu_electronic="<?php echo htmlspecialchars($cliente['correu_electronic']); ?>">
                                        <?php echo $cliente['id_usuari'] . " - " . $cliente['username']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="edit_username">Nombre de usuario:</label>
                            <input type="text" name="username" id="edit_username" required>
                        </div>
                        <div>
                            <label for="edit_contrasenya">Nueva Contraseña (dejar en blanco para no cambiar):</label>
                            <input type="password" name="contrasenya" id="edit_contrasenya">
                        </div>
                        <div>
                            <label for="edit_nom_usuari">Nombre:</label>
                            <input type="text" name="nom_usuari" id="edit_nom_usuari" required>
                        </div>
                        <div>
                            <label for="edit_cognom_usuari">Apellido:</label>
                            <input type="text" name="cognom_usuari" id="edit_cognom_usuari" required>
                        </div>
                        <div>
                            <label for="edit_correu_electronic">Correo Electrónico:</label>
                            <input type="email" name="correu_electronic" id="edit_correu_electronic" required>
                        </div>
                        <button type="submit" name="update_cliente">Actualizar Cliente</button>
                    </form>
                </div>
            </div>

            <h3>Lista de Clientes</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo $cliente['id_usuari']; ?></td>
                            <td><?php echo $cliente['username']; ?></td>
                            <td><?php echo $cliente['nom_usuari']; ?></td>
                            <td><?php echo $cliente['cognom_usuari']; ?></td>
                            <td><?php echo $cliente['correu_electronic']; ?></td>
                            <td><?php echo $cliente['rol']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="delete_cliente" value="<?php echo $cliente['id_usuari']; ?>">
                                    <button type="submit" onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function autofillCliente() {
            const select = document.getElementById('id_usuari');
            const selected = select.options[select.selectedIndex];

            document.getElementById('edit_username').value = selected.getAttribute('data-username') || '';
            document.getElementById('edit_nom_usuari').value = selected.getAttribute('data-nom_usuari') || '';
            document.getElementById('edit_cognom_usuari').value = selected.getAttribute('data-cognom_usuari') || '';
            document.getElementById('edit_correu_electronic').value = selected.getAttribute('data-correu_electronic') || '';
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