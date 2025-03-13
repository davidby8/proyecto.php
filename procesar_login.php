<?php
session_start(); 

include('conexion.php'); 


if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT id_usuari, contrasenya, rol FROM usuaris WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usuari, $hashed_password, $rol);
        $stmt->fetch();

        
        if (password_verify($password, $hashed_password)) {
            
            $_SESSION['id_usuari'] = $id_usuari;
            $_SESSION['username'] = $username;
            $_SESSION['rol'] = $rol;

            
            header("Location: panel.php");
            exit();
        } else {
            
            echo "Contraseña incorrecta.";
        }
    } else {
        
        echo "Nombre de usuario no encontrado.";
    }

    $stmt->close();
}

$conn->close();
?>
