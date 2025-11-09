<?php
// Iniciar la sesión
session_start();

// identificadores estáticos
const USUARIO_ESTATICO = 'admin';
const PASS_ESTATICO = 'pass123';

// Datos del formulario
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];
$color = $_POST['color'];

// Validación PHP por campos vacíos
if (empty($usuario) || empty($contrasena) || empty($color)) {
    // Redirigir de vuelta al login con un error
    header("Location: index.php?error=vacios");
    exit;
}

// Validación por fallo de credenciales
if ($usuario === USUARIO_ESTATICO && $contrasena === PASS_ESTATICO) {
    // Credenciales correctas: guardar en la sesión
    $_SESSION['usuario'] = $usuario;
    $_SESSION['color'] = $color;
    
    // Redirigir a la página de bienvenida
    header("Location: bienvenida.php");
    exit;
} else {
    // Credenciales incorrectas
    header("Location: index.php?error=credenciales");
    exit;
}
?>