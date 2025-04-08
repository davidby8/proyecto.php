<?php
session_start();

// Verificar si el usuario está logueado (si no está logueado, redirigir al login)
if (!isset($_SESSION['id_usuari'])) {
    header("Location: login.php"); // Redirigir al login si no está autenticado
    exit();
}

$host = "localhost"; 
$dbname = "Muebles"; 
$username = "danielgil"; 
$password = "12345678"; 

// Conexión a la base de datos (para catalogo.php)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consultar los productos de la tabla catalogo
    $stmt = $pdo->prepare("SELECT * FROM catalogo");
    $stmt->execute();
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error de conexión: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos - Muebles</title>
    <style>
        :root {
            --color-primario: #2c3e50;
            --color-secundario: #27ae60;
            --color-terciario: #3498db;
            --texto-claro: #ffffff;
            --texto-oscuro: #333333;
            --gris-claro: #ecf0f1;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--texto-oscuro);
            line-height: 1.6;
            background-color: var(--gris-claro);
        }

        header {
            background-color: var(--color-primario);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: var(--texto-claro);
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .auth-buttons a {
            color: var(--texto-claro);
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            padding: 40px;
        }

        .product-card {
            background-color: var(--texto-claro);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-card h3 {
            font-size: 1.4rem;
            padding: 15px;
            color: var(--color-primario);
        }

        .product-card p {
            padding: 0 15px 15px;
            color: #666;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: var(--color-secundario);
            color: var(--texto-claro);
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }

        .btn:hover {
            background-color: #2ecc71;
        }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="logo">Muebles</a>
    <div class="auth-buttons">
        <a href="logout.php" class="btn">Cerrar sesión</a>
    </div>
</header>

<main>
    <section class="product-grid">
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php foreach ($productos as $producto): ?>
            <div class="product-card">
                <img src="<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre_producto']; ?>">
                <h3><?php echo $producto['nombre_producto']; ?></h3>
                <p><?php echo $producto['descripcion']; ?></p>
                <p><strong>Precio: $<?php echo number_format($producto['precio'], 2); ?></strong></p>
            </div>
        <?php endforeach; ?>
    </section>
</main>

</body>
</html>
