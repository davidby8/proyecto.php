<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correu_electronic = $_POST['correu_electronic'];
    $contrasenya = $_POST['contrasenya'];

    $stmt = $conn->prepare("SELECT id_usuari, nom_usuari, contrasenya, rol FROM usuarios WHERE correu_electronic = :correu_electronic");
    $stmt->execute(['correu_electronic' => $correu_electronic]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($contrasenya, $user['contrasenya'])) {
        $_SESSION['user_id'] = $user['id_usuari'];
        $_SESSION['user_name'] = $user['nom_usuari'];
        $_SESSION['rol'] = $user['rol'];
        header('Location: dashboard.php');
    } else {
        $error = "Correo electrónico o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tienda de Muebles</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar sesión</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="correu_electronic">Correo electrónico:</label>
            <input type="email" id="correu_electronic" name="correu_electronic" required>

            <label for="contrasenya">Contraseña:</label>
            <input type="password" id="contrasenya" name="contrasenya" required>

            <input type="submit" value="Iniciar sesión">
        </form>
        <div class="login-link">
            ¿No tienes una cuenta? <a href="register.php">Regístrate</a>
        </div>
    </div>
</body>
</html>