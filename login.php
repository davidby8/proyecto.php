<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "DavidyDaniel Muebles";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['contrasenya'])) {
            $_SESSION['id_usuar'] = $user['id_usuar'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nom_usuari'] = $user['nom_usuari'];
            $_SESSION['cognom_usuari'] = $user['cognom_usuari'];
            $_SESSION['correu_electronic'] = $user['correu_electronic'];
            $_SESSION['rol'] = $user['rol'];

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }

    $conn->close();
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

    <?php
    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    ?>

    <form action="login.php" method="POST">
        <label for="username">Nombre de usuario:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>


