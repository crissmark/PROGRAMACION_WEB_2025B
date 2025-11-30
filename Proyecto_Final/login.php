<?php
// login.php
require 'includes/conexion.php';
require 'includes/helpers.php'; // Ya incluye session_start()

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = limpiar($_POST['email']);
    $password = $_POST['password'];

    // Buscar usuario por email
    $sql = "SELECT id, nombre, apellidos, password, rol_id FROM usuarios WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
        // Credenciales correctas: Guardamos datos en sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'] . " " . $usuario['apellidos'];
        $_SESSION['rol_id'] = $usuario['rol_id'];

        // REDIRECCIÓN SEGÚN ROL (Lógica de negocio)
        if ($usuario['rol_id'] == 1) {
            // Admin va a su panel de usuarios
            header("Location: home/usuarios/index.php");
        } elseif ($usuario['rol_id'] == 2) {
            // Maestro va a gestionar sus materias
            header("Location: home/materias/index.php"); 
        } else {
            // Estudiante va a ver sus clases
            header("Location: home/materias/mis_clases.php");
        }
        exit;

    } else {
        $mensaje = "Correo o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Plataforma Académica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="login-body">

<div class="card card-login shadow bg-white">
    <div class="text-center mb-4">
        <h3>🎓 Ingresar</h3>
        <p class="text-muted">Plataforma Académica</p>
    </div>

    <?php if(isset($_GET['registrado'])): ?>
        <div class="alert alert-success">¡Registro exitoso! Inicia sesión.</div>
    <?php endif; ?>

    <?php if($mensaje): ?>
        <div class="alert alert-danger"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="mb-3">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <div class="text-center mt-3">
        <a href="sign_up.php">¿No tienes cuenta? Regístrate aquí</a>
    </div>
</div>

</body>
</html>