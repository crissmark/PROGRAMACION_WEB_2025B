<?php
// Proyecto_Final/logout.php

session_start(); // Inicia la sesión para poder destruirla
session_unset(); // Limpia todas las variables de sesión
session_destroy(); // Destruye la sesión completamente

// Redirección absoluta al login para evitar errores de ruta
header("Location: /Proyecto_Final/login.php");
exit;
?>