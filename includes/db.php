<?php
$host = 'localhost';      
$dbname = 'DavidyDaniel Muebles';     
$username = 'danielgil';       
$password = '12345678';           

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
