<?php
// home/materias/crear.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// Seguridad: Solo Maestros
if ($_SESSION['rol_id'] != 2) {
    header("Location: ../index.php");
    exit;
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = limpiar($_POST['nombre']);
    $descripcion = limpiar($_POST['descripcion']);
    $clave = limpiar($_POST['clave_acceso']);
    $maestro_id = $_SESSION['usuario_id']; // El creador es el usuario logueado

    if (empty($nombre)) {
        $mensaje = "El nombre de la materia es obligatorio.";
    } else {
        $sql = "INSERT INTO materias (nombre, descripcion, maestro_id, clave_acceso) 
                VALUES (:nombre, :desc, :maestro, :clave)";
        
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([
            ':nombre' => $nombre, 
            ':desc' => $descripcion, 
            ':maestro' => $maestro_id,
            ':clave' => $clave
        ])) {
            header("Location: index.php");
            exit;
        } else {
            $mensaje = "Error al crear la materia.";
        }
    }
}

require '../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Nueva Materia</h5>
            </div>
            <div class="card-body">
                
                <?php if($mensaje): ?>
                    <div class="alert alert-danger"><?= $mensaje ?></div>
                <?php endif; ?>

                <form method="POST" action="crear.php">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Materia *</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Matemáticas I" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Clave de Acceso (Opcional)</label>
                        <input type="text" name="clave_acceso" class="form-control" placeholder="Ej: MAT2025">
                        <div class="form-text">Para que los alumnos se inscriban con contraseña.</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Guardar Materia</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../../includes/footer.php'; ?>