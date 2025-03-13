<?php
include '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    echo "Producto eliminado con éxito.";
}
?>
