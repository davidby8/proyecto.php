<?php
session_start();
<<<<<<< HEAD

if (isset($_SESSION['user_id'])) {
        header('Location: dashboard.php');
    exit;
} else {
        header('Location: login.php');
    exit;
}
?>
=======
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar credenciales
    $stmt = $conn->prepare("SELECT id, nombre, password FROM usuarios WHERE correu_electronic = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Iniciar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'];
        header('Location: pages/dashboard.php');
        exit;
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
        <form action="index.php" method="post">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Iniciar sesión">
        </form>
        <div class="login-link">
            ¿No tienes una cuenta? <a href="#">Regístrate</a>
        </div>
    </div>
</body>
</html>
>>>>>>> 7d1397a (indexphp1)
