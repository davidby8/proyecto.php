<?php
include '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $producto = $stmt->fetch();

    echo "<form action='' method='POST' enctype='multipart/form-data'>";
    echo "<input type='hidden' name='id' value='{$producto['id']}'>";
    echo "<input type='text' name='nombre' value='{$producto['nombre']}' required><br>";
    echo "<textarea name='descripcion'>{$producto['descripcion']}</textarea><br>";
    echo "<input type='number' name='precio' value='{$producto['precio']}' required><br>";
    echo "<input type='file' name='imagen'><br>";
    echo "<button type='submit' name='actualizar'>Actualizar Producto</button>";
    echo "</form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen']['name'];

    if ($_FILES['imagen']['error'] == 0) {
        move_uploaded_file($_FILES['imagen']['tmp_name'], '../uploads/' . $imagen);
    }

    $stmt = $pdo->prepare("UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, imagen = :imagen WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':imagen', $imagen);
    $stmt->execute();

    echo "Producto actualizado con éxito.";
}
?>
