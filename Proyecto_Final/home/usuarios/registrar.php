<?php
// home/usuarios/registrar.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// Solo Admin
if ($_SESSION['rol_id'] != 1) { header("Location: ../index.php"); exit; }

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = limpiar($_POST['nombre']);
    $apellidos = limpiar($_POST['apellidos']);
    $email = limpiar($_POST['email']);
    $password = $_POST['password'];
    $rol_id = $_POST['rol_id'];

    // Validar correo duplicado
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
    $check->execute([':email' => $email]);

    if ($check->rowCount() > 0) {
        $mensaje = "El correo ya está registrado.";
    } else {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, rol_id) 
                VALUES (:nom, :ape, :email, :pass, :rol)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([':nom'=>$nombre, ':ape'=>$apellidos, ':email'=>$email, ':pass'=>$pass_hash, ':rol'=>$rol_id])) {
            header("Location: index.php");
            exit;
        } else {
            $mensaje = "Error al guardar.";
        }
    }
}

require '../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-dark text-white"><h5>Nuevo Usuario</h5></div>
            <div class="card-body">
                <?php if($mensaje): ?><div class="alert alert-danger"><?= $mensaje ?></div><?php endif; ?>
                
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col">
                            <label>Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Rol</label>
                        <select name="rol_id" class="form-select">
                            <option value="3">Estudiante</option>
                            <option value="2">Maestro</option>
                            <option value="1">Administrador</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Guardar Usuario</button>
                    <a href="index.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require '../../includes/footer.php'; ?>