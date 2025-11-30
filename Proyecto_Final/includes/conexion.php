<?php
// includes/conexion.php

$host = 'localhost';
$dbname = 'plataforma_academica'; // <--- Nombre de la nueva BD
$usuario = 'root';
$clave = 'root'; // la contraseña por defecto es root al igual que el usuario!!

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $clave);
    
    // Configuracion de errores 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>