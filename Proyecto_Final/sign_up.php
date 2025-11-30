<?php
// sign_up.php
require 'includes/conexion.php';
require 'includes/helpers.php'; // Para usar la función limpiar

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpiamos los datos 
    $nombre = limpiar($_POST['nombre']);
    $apellidos = limpiar($_POST['apellidos']);
    $email = limpiar($_POST['email']);
    $password = $_POST['password'];
    $rol_id = limpiar($_POST['rol_id']); // 2=Maestro, 3=Estudiante

    // Validaciones 
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($password) || empty($rol_id)) {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        // Verificar el correo 
        $check = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
        $check->execute([':email' => $email]);
        
        if ($check->rowCount() > 0) {
            $mensaje = "Este correo ya está registrado.";
        } else {
            // Encriptar contraseña y Registrar
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, rol_id) 
                    VALUES (:nombre, :apellidos, :email, :pass, :rol)";
            
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([
                ':nombre' => $nombre, 
                ':apellidos' => $apellidos,
                ':email' => $email, 
                ':pass' => $pass_hash, 
                ':rol' => $rol_id
            ])) {
                // Va hacia al login con mensaje de éxito 
                header("Location: login.php?registrado=1");
                exit;
            } else {
                $mensaje = "Error al registrar usuario.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Plataforma Académica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="login-body">

<div class="card card-registro shadow bg-white">
    <h3 class="text-center mb-4">Crear Cuenta</h3>
    
    <?php if($mensaje): ?>
        <div class="alert alert-danger"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST" action="sign_up.php">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellidos</label>
                <input type="text" name="apellidos" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Soy:</label>
            <select name="rol_id" class="form-select">
                <option value="3">Estudiante</option>
                <option value="2">Maestro</option>
            </select>
            <div class="form-text">Selecciona tu perfil correctamente.</div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
    </form>
    <div class="text-center mt-3">
        <a href="login.php">¿Ya tienes cuenta? Inicia Sesión</a>
    </div>
</div>

</body>
</html>