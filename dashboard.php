<?php
session_start();

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Usuario - Muebles</title>
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
    <h1>Bienvenido a Muebles</h1>
  </header>

  <nav>
    <a href="#">Inicio</a>
    <a href="#">Catálogo</a>
    <a href="#">Perfil</a>
    <a href="logout.php">Cerrar sesión</a>
  </nav>

  <div class="content">
    <h2>¡Has iniciado sesión correctamente!</h2>
    <p>Aqui veras nuestro catalogo de muebles, haz tus pedidos y mucho más.</p>
    <form action="logout.php" method="post">
      <button class="logout-btn">Cerrar sesión</button>
    </form>
  </div>

  <footer>
    © Muebles.
  </footer>

</body>
</html>