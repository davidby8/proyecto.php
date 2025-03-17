<?php
session_start();

$host = "localhost"; 
$dbname = "Muebles";
$username = "daniel"; 
$password = "C@ramelo2003"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
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
            echo "<div class='error'>Usuario o contraseña incorrectos.</div>";
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
    <title>Iniciar Sesión - Muebles</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            padding: 20px;
            text-align: center;
            color: #fff;
        }

        header h1 {
            margin: 0;
        }

        nav {
            margin-top: 10px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin: 0 10px;
        }

        .login-section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            background-color: #ecf0f1;
        }

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .input-group input:focus {
            border-color: #3498db;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #27ae60;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }

        button:hover {
            background-color: #2ecc71;
        }

        .error {
            color: red;
            text-align: center;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #2c3e50;
            color: #fff;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<header>
    <h1>Muebles</h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="catalogo.php">Catálogo</a>
        <a href="contacto.php">Contacto</a>
        <a href="register.php" class="btn">Registrarse</a>
    </nav>
</header>

<section class="login-section">
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="">
            <div class="input-group">
                <input type="text" name="usuario" placeholder="Usuario" required>
            </div>
            <div class="input-group">
                <input type="password" name="contraseña" placeholder="Contraseña" required>
            </div>
            <button type="submit" name="login">Iniciar Sesión</button>
        </form>
    </div>
</section>

<footer>
    © 2025 Muebles.
</footer>

</body>
</html>



