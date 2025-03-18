<?php
$host = "localhost"; 
$dbname = "Muebles";
$username = "daniel"; 
$password = "C@ramelo2003"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $contrasenya = $_POST['contrasenya'];
    $correu_electronic = $_POST['correu_electronic'];
    $nom_usuari = $_POST['nom_usuari'];
    $cognom_usuari = $_POST['cognom_usuari'];

    try {
        // Conexión a la base de datos
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Hashear la contraseña
        $hashedPassword = password_hash($contrasenya, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario en la base de datos
        $sql = "INSERT INTO usuaris (username, contrasenya, correu_electronic, nom_usuari, cognom_usuari) 
                VALUES (:username, :contrasenya, :correu_electronic, :nom_usuari, :cognom_usuari)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':contrasenya', $hashedPassword);
        $stmt->bindParam(':correu_electronic', $correu_electronic);
        $stmt->bindParam(':nom_usuari', $nom_usuari);
        $stmt->bindParam(':cognom_usuari', $cognom_usuari);
        $stmt->execute();

        // Mensaje de éxito
        echo "<div class='success'>Usuario registrado exitosamente. <a href='login.php'>Iniciar sesión</a></div>";
    } catch (PDOException $e) {
        // Manejo de errores
        echo "<div class='error'>Error de conexión: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse - Muebles</title>
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

        .register-section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            background-color: #ecf0f1;
        }

        .register-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
        }

        .register-container h2 {
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

        .success {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
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
        <a href="login.php" class="btn">Iniciar Sesión</a>
    </nav>
</header>

<section class="register-section">
    <div class="register-container">
        <h2>Registrarse</h2>
        <form method="POST" action="">
            <div class="input-group">
                <input type="text" name="username" placeholder="Usuario" required>
            </div>
            <div class="input-group">
                <input type="password" name="contrasenya" placeholder="Contraseña" required>
            </div>
            <div class="input-group">
                <input type="email" name="correu_electronic" placeholder="Correo Electrónico" required>
            </div>
            <div class="input-group">
                <input type="text" name="nom_usuari" placeholder="Nombre" required>
            </div>
            <div class="input-group">
                <input type="text" name="cognom_usuari" placeholder="Apellido" required>
            </div>
            <button type="submit" name="register">Registrarse</button>
        </form>
    </div>
</section>

<footer>
    © 2025 Muebles.
</footer>

</body>
</html>