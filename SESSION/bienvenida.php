<?php
session_start();

// Validar que el usuario esté autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=noauth");
    exit;
}

// Obtener el color de la sesión
$color = $_SESSION['color'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Aplicamos el color al texto de la página */
        body {
            color: <?php echo htmlspecialchars($color); ?>;
        }
        .card {
            color: #212529; /* Color de texto normal para la tarjeta */
        }
    </style>
</head>
<body style="color: <?php echo htmlspecialchars($color); ?>;">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm text-center">
                    <div class="card-header">
                        <h1 class="h4 mb-0">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h1>
                    </div>
                    <div class="card-body">
                        <p class="card-text" style="color: #212529;">Elige cómo quieres rastrear tus visitas:</p>
                        
                        <a href="sesiones.php" class="btn btn-primary">Contador con SESIONES</a>
                        <a href="cookies.php" class="btn btn-success">Contador con COOKIES</a>
                        
                        <hr>
                        <a href="logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>