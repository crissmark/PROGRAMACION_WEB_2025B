<?php
// Proyecto_Final/index.php
session_start();

// evitar que se cuelen 
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['rol_id'] == 1) {
        header("Location: home/usuarios/index.php");
    } elseif ($_SESSION['rol_id'] == 2) {
        header("Location: home/materias/index.php");
    } else {
        header("Location: home/materias/mis_clases.php");
    }
} else {
    // los mandara al login!!!
    header("Location: login.php");
}
exit;
?>