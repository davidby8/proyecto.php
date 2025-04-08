<?php
session_start();

// Habilitar el reporte de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

    // Manejar la imagen (si está usando URL)
    $imagen = $_POST['imagen'];  // Si planeas cargar imágenes desde URL

    // Insertar el producto en la base de datos
    try {
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $precio, $imagen]);
        $success = "Producto añadido correctamente.";
    } catch (PDOException $e) {
        $error = "Error al añadir el producto: " . $e->getMessage();
    }
}

// Eliminar un producto
if (isset($_POST['delete_product'])) {
    $id_producto = $_POST['delete_product'];

    // Eliminar el producto de la base de datos
    try {
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id_producto = ?");
        $stmt->execute([$id_producto]);
        $success = "Producto eliminado correctamente.";
    } catch (PDOException $e) {
        $error = "Error al eliminar el producto: " . $e->getMessage();
    }
}

// Obtener todos los productos
try {
    $stmt = $pdo->prepare("SELECT * FROM productos");
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
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        .success {
            color: green;
            background-color: #d4edda;
            padding: 10px;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            background-color: #f8d7da;
            padding: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        button {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #2ecc71;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Dashboard Administrador</h2>

    <!-- Mensajes de éxito o error -->
    <?php if (isset($success)): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Formulario para añadir un producto -->
    <div class="form-container">
        <h3>Añadir Producto</h3>
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
            <button type="submit" name="add_product">Añadir Producto</button>
        </form>
    </div>

    <!-- Tabla para mostrar productos -->
    <h3>Lista de Productos</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo $producto['id_producto']; ?></td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td><?php echo $producto['precio']; ?> €</td>
                    <td><img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" width="50"></td>
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

</body>
</html>
