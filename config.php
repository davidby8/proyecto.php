<?php
$host = 'localhost';
$dbname = 'Muebles';
$username = 'daniel';
$password = 'C@ramelo2003';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    
    error_log("Error de conexión: " . $e->getMessage());
    
    die("Hubo un problema al conectar con la base de datos. Por favor, inténtelo de nuevo más tarde.");
}
?>
