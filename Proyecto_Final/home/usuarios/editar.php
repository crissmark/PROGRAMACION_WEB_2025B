<?php
// home/usuarios/editar.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();
if ($_SESSION['rol_id'] != 1) { header("Location: ../index.php"); exit; }

$id = $_GET['id'];
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = limpiar($_POST['nombre']);
    $apellidos = limpiar($_POST['apellidos']);
    $email = limpiar($_POST['email']);
    $rol_id = $_POST['rol_id'];
    $password = $_POST['password']; // Opcional

    // Actualización dinámica: Si puso password, la cambiamos; si no, la dejamos igual
    if (!empty($password)) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET nombre=:nom, apellidos=:ape, email=:email, rol_id=:rol, password=:pass WHERE id=:id";
        $params = [':nom'=>$nombre, ':ape'=>$apellidos, ':email'=>$email, ':rol'=>$rol_id, ':pass'=>$pass_hash, ':id'=>$id];
    } else {
        $sql = "UPDATE usuarios SET nombre=:nom, apellidos=:ape, email=:email, rol_id=:rol WHERE id=:id";
        $params = [':nom'=>$nombre, ':ape'=>$apellidos, ':email'=>$email, ':rol'=>$rol_id, ':id'=>$id];
    }

    $stmt = $conn->prepare($sql);
    if ($stmt->execute($params)) {
        header("Location: index.php");
        exit;
    } else {
        $mensaje = "Error al actualizar.";
    }
}

// Obtener datos actuales
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

require '../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-warning"><h5>Editar Usuario</h5></div>
            <div class="card-body">
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?= $usuario['nombre'] ?>" required>
                        </div>
                        <div class="col">
                            <label>Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" value="<?= $usuario['apellidos'] ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $usuario['email'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Contraseña (Dejar en blanco para no cambiar)</label>
                        <input type="password" name="password" class="form-control" placeholder="********">
                    </div>
                    <div class="mb-3">
                        <label>Rol</label>
                        <select name="rol_id" class="form-select">
                            <option value="3" <?= $usuario['rol_id']==3 ? 'selected':'' ?>>Estudiante</option>
                            <option value="2" <?= $usuario['rol_id']==2 ? 'selected':'' ?>>Maestro</option>
                            <option value="1" <?= $usuario['rol_id']==1 ? 'selected':'' ?>>Administrador</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Actualizar Datos</button>
                    <a href="index.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require '../../includes/footer.php'; ?>