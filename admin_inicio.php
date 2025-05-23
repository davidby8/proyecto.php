<?php
session_start();

// Verificar si el usuario está logueado y es un admin
if (!isset($_SESSION['id_usuari']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Obtener nombre de usuario para mostrar
$nombre_usuario = isset($_SESSION['nom_usuari']) ? $_SESSION['nom_usuari'] : 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            text-align: center;
        }
        
        .options {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }
        
        .option-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 300px;
            transition: transform 0.3s;
        }
        
        .option-card:hover {
            transform: translateY(-5px);
        }
        
        .btn {
            display: inline-block;
            background: #1abc9c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
        }
        
        .btn:hover {
            background: #16a085;
        }
    </style>
</head>
<body>
    <header>
        <h1>Panel de Administración</h1>
    </header>
    
    <div class="container">
        <h2>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></h2>
        <p>Seleccione el área que desea gestionar:</p>
        
        <div class="options">
            <div class="option-card">
                <h3>Gestión de Productos</h3>
                <p>Administre el catálogo de productos</p>
                <a href="admin_dashboard.php" class="btn">Ir a Productos</a>
            </div>
            
            <div class="option-card">
                <h3>Gestión de Clientes</h3>
                <p>Administre los clientes registrados</p>
                <a href="dashboard_clientes_admin.php" class="btn">Ir a Clientes</a>
            </div>
        </div>
    </div>
</body>
</html>