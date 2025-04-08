<?php
$host = 'localhost';      // Dirección del servidor de base de datos
$dbname = 'Muebles';      // Nombre de la base de datos
$username = 'danielgil';  // Nombre de usuario de la base de datos
$password = '12345678';   // Contraseña del usuario

try {
    // Conexión a la base de datos con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Establecer el modo de error a excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configurar la codificación de caracteres a UTF-8
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    // Si ocurre un error en la conexión, se registra el error
    error_log("Error de conexión: " . $e->getMessage(), 3, 'errors.log');
    
    // Mostrar mensaje de error amigable al usuario
    die("Hubo un problema al conectar con la base de datos. Por favor, inténtelo de nuevo más tarde.");
}
?>
