<?php

session_start();


$host = "localhost"; 
$dbname = "DavidyDaniel Muebles";
$username = "root"; 
$password = "C@ramelo2003"; 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        
        $usuarioEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuarioEncontrado && password_verify($contraseña, $usuarioEncontrado['contraseña'])) {
            
            $_SESSION['id_usuario'] = $usuarioEncontrado['id_usuario'];
            $_SESSION['usuario'] = $usuarioEncontrado['usuario'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Usuario o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Muebles- Iniciar Sesión</title>
  <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #28a745;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    margin: 5px;
}

.btn:hover {
    background-color: #218838;
}

.error {
    color: red;
    text-align: center;
}


.header {
    background-color: #2c3e50;
    color: #fff;
    padding: 60px 0;
    text-align: center;
}

.header h1 {
    font-size: 3rem;
    margin: 0;
}

.header p {
    font-size: 1.2rem;
    margin: 10px 0 0;
}

.hero {
    background: url('https://via.placeholder.com/1200x400') no-repeat center center/cover;
    color: #fff;
    padding: 100px 0;
    text-align: center;
}

.hero h2 {
    font-size: 2.5rem;
    margin: 0;
}

.hero p {
    font-size: 1.2rem;
    margin: 10px 0 20px;
}

.hero .btn {
    background-color: #e67e22;
    color: #fff;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    margin: 5px;
}

.hero .btn-secondary {
    background-color: #34495e;
}

.about {
    padding: 60px 0;
    text-align: center;
    background-color: #fff;
}

.about h2 {
    font-size: 2rem;
    margin-bottom: 20px;
}

.about p {
    font-size: 1.1rem;
    max-width: 800px;
    margin: 0 auto;
}

.featured-products {
    padding: 60px 0;
    background-color: #f9f9f9;
    text-align: center;
}

.featured-products h2 {
    font-size: 2rem;
    margin-bottom: 40px;
}

.product-grid {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 20px;
}

.product-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    width: 300px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-10px);
}

.product-card img {
    width: 100%;
    border-radius: 8px;
}

.product-card h3 {
    font-size: 1.5rem;
    margin: 15px 0 10px;
}

.product-card p {
    font-size: 1rem;
    color: #666;
}

.footer {
    background-color: #2c3e50;
    color: #fff;
    text-align: center;
    padding: 20px 0;
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
    <h1>Muebles</h1>
  </header>

  <nav>
    <a href="#">Inicio</a>
    <a href="#">Catálogo</a>
    <a href="#">Sobre Nosotros</a>
    <a href="#">Contacto</a>
    <a href="login.php" class="login-btn">Iniciar Sesión</a>
  </nav>

  <div class="login-container">
    <div class="login-box">
      <h2>Iniciar Sesión</h2>
      <form method="POST" action="">
        <input type="text" name="usuario" placeholder="Usuario" required><br>
        <input type="password" name="contraseña" placeholder="Contraseña" required><br>
        <button type="submit">Iniciar Sesión</button>
      </form>
    </div>
  </div>

  <footer>
    © 2025 Muebles.
  </footer>

</body>
</html>