<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Lógica para agregar, editar y eliminar productos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agregar'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];

        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio) VALUES (:nombre, :descripcion, :precio)");
        $stmt->execute(['nombre' => $nombre, 'descripcion' => $descripcion, 'precio' => $precio]);
    } elseif (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];

        $stmt = $conn->prepare("UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio WHERE id = :id");
        $stmt->execute(['id' => $id, 'nombre' => $nombre, 'descripcion' => $descripcion, 'precio' => $precio]);
    } elseif (isset($_POST['eliminar'])) {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM productos WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

$stmt = $conn->query("SELECT * FROM productos");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Tienda de Muebles</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Gestión de Productos</h2>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="descripcion" placeholder="Descripción" required>
            <input type="number" name="precio" placeholder="Precio" required>
            <button type="submit" name="agregar">Agregar Producto</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['id']; ?></td>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td><?php echo $producto['descripcion']; ?></td>
                        <td><?php echo $producto['precio']; ?>€</td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                                <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>">
                                <input type="text" name="descripcion" value="<?php echo $producto['descripcion']; ?>">
                                <input type="number" name="precio" value="<?php echo $producto['precio']; ?>">
                                <button type="submit" name="editar">Editar</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                                <button type="submit" name="eliminar">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>