<?php
session_start();

// Simulación de un usuario y contraseña almacenados (esto se debe hacer en una base de datos real)
$usuarios = [
    'usuario1' => 'contraseña1',
    'usuario2' => 'contraseña2',
];

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Comprobar si el usuario y la contraseña coinciden
    if (isset($usuarios[$usuario]) && $usuarios[$usuario] == $contraseña) {
        // Iniciar sesión y redirigir
        $_SESSION['usuario'] = $usuario;
        header('Location: bienvenido.php');
        exit();
    } else {
        // Mostrar mensaje de error
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Iniciar sesión</h2>

    <!-- Mostrar mensaje de error si existe -->
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
        <br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required>
        <br><br>
        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>
