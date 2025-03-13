<?php
session_start();

$valid_username = "admin";
$valid_password = "password123";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['user_name'] = $username;
        $_SESSION['user_id'] = 1;

        
        header("Location: dashboard.php");
        exit();
    } else {
        
        echo "Credenciales incorrectas.";
    }
} else {
    echo "Por favor, ingrese las credenciales.";
}
?>
