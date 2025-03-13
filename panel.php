<?php
session_start(); 


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


echo "<h2>Bienvenido, " . $_SESSION['username'] . "</h2>";

echo "<p>Rol: " . $_SESSION['rol'] . "</p>";


if ($_SESSION['rol'] == 'admin') {
    echo "<p>Acceso de administrador</p>";

} else {
    echo "<p>Acceso de usuario normal</p>";

}

echo '<a href="logout.php">Cerrar sesión</a>';
?>
