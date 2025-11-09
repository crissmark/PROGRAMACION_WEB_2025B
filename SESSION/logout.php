<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Borrar la cookie de sesión (si se usa)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión.
session_destroy();

/*
Opcional: También podemos borrar la cookie del contador
Si no lo hacemos, el contador seguirá aunque otro usuario inicie sesión.
Para la práctica, es bueno ver cómo persiste. 
Si quieres borrarla, descomenta la siguiente línea:
*/
// setcookie('contador_cookie', '', time() - 3600, "/");

// Redirigir al formulario de login
header("Location: index.php");
exit;
?>