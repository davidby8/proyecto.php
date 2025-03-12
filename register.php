<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $nom_usuari = $_POST['nom_usuari'];
    $cognom_usuari = $_POST['cognom_usuari'];
    $correu_electronic = $_POST['correu_electronic'];
    $contrasenya = password_hash($_POST['contrasenya'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (username, nom_usuari, cognom_usuari, correu_electronic, contrasenya) VALUES (:username, :nom_usuari, :cognom_usuari, :correu_electronic, :contrasenya)");
    $stmt->execute([
        'username' => $username,
        'nom_usuari' => $nom_usuari,
        'cognom_usuari' => $cognom_usuari,
        'correu_electronic' => $correu_electronic,
        'contrasenya' => $contrasenya
    ]);

    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Tienda de Muebles</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registro</h2>
        <form method="POST">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" required>

            <label for="nom_usuari">Nombre:</label>
            <input type="text" id="nom_usuari" name="nom_usuari" required>

            <label for="cognom_usuari">Apellido:</label>
            <input type="text" id="cognom_usuari" name="cognom_usuari" required>

            <label for="correu_electronic">Correo electrónico:</label>
            <input type="email" id="correu_electronic" name="correu_electronic" required>

            <label for="contrasenya">Contraseña:</label>
            <input type="password" id="contrasenya" name="contrasenya" required>

            <input type="submit" value="Registrarse">
        </form>
        <div class="login-link">
            ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a>
        </div>
    </div>
</body>
</html>