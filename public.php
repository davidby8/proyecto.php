<?php
include 'includes/db.php';

$stmt = $conn->query("SELECT * FROM productos");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Tienda de Muebles</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Nuestros Productos</h2>
        <div class="productos">
            <?php foreach ($productos as $producto): ?>
                <div class="producto">
                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    <p class="precio">Precio: <?php echo htmlspecialchars($producto['precio']); ?>€</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>