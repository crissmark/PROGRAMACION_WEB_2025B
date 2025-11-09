<?php
session_start(); // Iniciamos sesión para validar al usuario y obtener el color

// Validar que el usuario esté autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=noauth");
    exit;
}

// Lógica del contador de cookie
$visitas = 1;
if (isset($_COOKIE['contador_cookie'])) {
    // Leemos la cookie y la incrementamos
    $visitas = (int)$_COOKIE['contador_cookie'] + 1;
}
// Creamos/Actualizamos la cookie para que dure 1 año
// (Debe hacerse ANTES de cualquier salida HTML)
setcookie('contador_cookie', (string)$visitas, time() + 60 * 60 * 24 * 365, "/");

$color = $_SESSION['color'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contador de Cookie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="color: <?php echo htmlspecialchars($color); ?>;">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm text-center">
                    <div class="card-body" style="color: #212529;">
                        <h2 style="color: <?php echo htmlspecialchars($color); ?>;">
                            Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                        </h2>
                        <h1 class="display-4">Contador (COOKIES)</h1>
                        <p class="lead">Has visitado esta página:</p>
                        <h2 class="display-1"><?php echo $visitas; ?></h2>
                        <p class="text-muted">Este contador persistirá incluso si cierras el navegador.</p>
                        
                        <hr>
                        <a href="bienvenida.php" class="btn btn-secondary">&laquo; Volver</a>
                        <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>