<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen']['name'];

    move_uploaded_file($_FILES['imagen']['tmp_name'], '../uploads/' . $imagen);

    $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (:nombre, :descripcion, :precio, :imagen)");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':imagen', $imagen);
    $stmt->execute();

    echo "Producto agregado con éxito.";
}
?>

<form action="agregar_producto.php" method="POST" enctype="multipart/form-data">
    <label for="nombre">Nombre del Producto:</label>
    <input type="text" name="nombre" required><br>

    <label for="descripcion">Descripción:</label>
    <textarea name="descripcion" required></textarea><br>

    <label for="precio">Precio:</label>
    <input type="number" name="precio" required><br>

    <label for="imagen">Imagen:</label>
    <input type="file" name="imagen" required><br>

    <button type="submit" name="crear">Añadir Producto</button>
</form>
