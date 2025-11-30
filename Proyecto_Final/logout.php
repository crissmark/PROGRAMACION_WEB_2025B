<?php
// Proyecto_Final/logout.php

session_start(); // Inicia la sesion para poder destruirla
session_unset(); // Limpia todas las variables de sesion
session_destroy(); // Destruye la sesion completamente

// Redireccion al login 
header("Location: /Proyecto_Final/login.php");
exit;
?>