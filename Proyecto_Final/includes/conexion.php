<?php
// includes/conexion.php

$host = 'localhost';
$dbname = 'plataforma_academica'; // <--- Nombre de la nueva BD
$usuario = 'root';
$clave = 'root'; // Si usas WAMP suele ser vacío, si usas XAMPP también. Cambia a 'root' si tienes contraseña.

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $clave);
    
    // Configuración de errores igual que en tus ejercicios anteriores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>