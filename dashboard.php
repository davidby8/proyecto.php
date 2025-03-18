<?php
session_start();

if (!isset($_SESSION['id_usuari'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

try {
    $stmt = $pdo->prepare("SELECT nom_usuari, cognom_usuari FROM usuaris WHERE id_usuari = :id_usuari");
    $stmt->bindParam(':id_usuari', $_SESSION['id_usuari']);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener la información del usuario: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Usuario - Muebles</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #2c3e50;
      color: #fff;
      padding: 20px 0;
      text-align: center;
    }

    header h1 {
      font-size: 2.5rem;
      margin: 0;
    }

    nav {
      display: flex;
      justify-content: center;
      background-color: #34495e;
      padding: 10px 0;
    }

    nav a {
      color: #fff;
      padding: 10px 20px;
      text-decoration: none;
      font-size: 1.1rem;
      transition: background-color 0.3s ease;
    }

    nav a:hover {
      background-color: #1abc9c;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: 30px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .content h2 {
      font-size: 2rem;
      color: #2c3e50;
      margin-bottom: 10px;
    }

    .content p {
      font-size: 1.2rem;
      color: #34495e;
      margin: 20px 0;
    }

    .logout-btn {
      display: inline-block;
      padding: 12px 25px;
      background-color: #e67e22;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      font-size: 1rem;
      transition: background-color 0.3s ease;
      margin-top: 20px;
    }

    .logout-btn:hover {
      background-color: #d35400;
    }

    .footer {
      background-color: #2c3e50;
      color: #fff;
      text-align: center;
      padding: 20px;
      margin-top: 40px;
    }

    .footer p {
      margin: 0;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

  <header>
    <h1>Bienvenido a Muebles, <?php echo htmlspecialchars($usuario['nom_usuari']); ?></h1>
  </header>

  <nav>
    <a href="#">Inicio</a>
    <a href="#">Catálogo</a>
    <a href="#">Perfil</a>
    <a href="logout.php">Cerrar sesión</a>
  </nav>

  <div class="container">
    <div class="content">
      <h2>¡Has iniciado sesión correctamente!</h2>
      <p>Ahora puedes acceder al catálogo de muebles, hacer pedidos y gestionar tu perfil.</p>
      <a href="logout.php" class="logout-btn">Cerrar sesión</a>
    </div>
  </div>

  <footer>
    <p>© 2025 Muebles. Todos los derechos reservados.</p>
  </footer>

</body>
</html>