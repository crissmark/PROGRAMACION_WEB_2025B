<?php
// includes/helpers.php

// Iniciamos la sesión como dijo el profe
session_start();

// Funcion para verificar si el usuario está logueado
// Si no esta logueado, lo manda al login!!!
function verificar_sesion() {
    if (!isset($_SESSION['usuario_id'])) {

        header("Location: /Proyecto_Final/login.php");
        exit;
    }
}

// Función para limpiar datos 
function limpiar($datos) {
    return htmlspecialchars($datos, ENT_QUOTES, 'UTF-8');
}

// Función para verificar si es Admin (Rol 1)
function es_admin() {
    return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1;
}

// Función para verificar si es Maestro (Rol 2)
function es_maestro() {
    return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 2;
}
?>