<?php
// Iniciar la sesión 
session_start();

$error = null;
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'vacios') {
        $error = "Error: Todos los campos son obligatorios.";
    } elseif ($_GET['error'] == 'credenciales') {
        $error = "Error: Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividad 15 - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h1 class="h4 text-center mb-0">Actividad 15 - Autenticación</h1>
                    </div>
                    <div class="card-body">
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                            <p>Usuario: admin</p>
                            <p>Contraseña: pass123</p>
                        <form action="validar.php" method="POST">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario:</label>
                                <input type="text" class="form-control" name="usuario" id="usuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" name="contrasena" id="contrasena" required>
                            </div>
                            <div class="mb-3">
                                <label for="color" class="form-label">Selecciona tu color favorito:</label>
                                <input type="color" class="form-control form-control-color" name="color" id="color" value="#000000" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <a href="/index.html" class="btn btn-outline-primary mt-3">Volver al INICIO</a>

</body>
</html>