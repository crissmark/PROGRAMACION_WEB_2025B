<?php
// home/materias/calificar.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// Solo Maestros
if ($_SESSION['rol_id'] != 2) { header("Location: ../index.php"); exit; }

$tarea_id = $_GET['id'];

// Procesar Calificación
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entrega_id = $_POST['entrega_id'];
    $nota = $_POST['calificacion'];
    $retro = limpiar($_POST['retroalimentacion']);

    $sql = "UPDATE entregas SET calificacion = :nota, comentarios = :retro WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':nota' => $nota, ':retro' => $retro, ':id' => $entrega_id]);
    
    // Recargar para ver cambios
    header("Location: calificar.php?id=$tarea_id&guardado=1");
    exit;
}

// Obtener info de la tarea
$stmt_tarea = $conn->prepare("SELECT titulo, materia_id FROM tareas WHERE id = :id");
$stmt_tarea->execute([':id' => $tarea_id]);
$tarea = $stmt_tarea->fetch(PDO::FETCH_ASSOC);

// Obtener entregas de alumnos
$sql = "SELECT e.*, u.nombre, u.apellidos 
        FROM entregas e 
        JOIN usuarios u ON e.alumno_id = u.id 
        WHERE e.tarea_id = :tid";
$stmt = $conn->prepare($sql);
$stmt->execute([':tid' => $tarea_id]);
$entregas = $stmt->fetchAll(PDO::FETCH_ASSOC);

require '../../includes/header.php';
?>

<div class="row mb-3">
    <div class="col-md-8">
        <h3>📝 Calificar: <?= limpiar($tarea['titulo']) ?></h3>
        <a href="ver_materia.php?id=<?= $tarea['materia_id'] ?>" class="btn btn-outline-secondary btn-sm">⬅ Volver a Materia</a>
    </div>
</div>

<?php if(isset($_GET['guardado'])): ?>
    <div class="alert alert-success">✅ Calificación guardada.</div>
<?php endif; ?>

<div class="row">
    <?php if(count($entregas) > 0): ?>
        <?php foreach ($entregas as $fila): ?>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= limpiar($fila['nombre'] . " " . $fila['apellidos']) ?></h5>
                        <p class="text-muted small">Enviado: <?= $fila['fecha_entrega'] ?></p>
                        
                        <?php if($fila['archivo']): ?>
                            <a href="../../assets/uploads/<?= $fila['archivo'] ?>" target="_blank" class="btn btn-sm btn-info text-white mb-2">📄 Ver Archivo</a>
                        <?php endif; ?>
                        
                        <p class="card-text bg-light p-2 rounded"><?= limpiar($fila['comentarios']) ?></p>
                        
                        <form method="POST">
                            <input type="hidden" name="entrega_id" value="<?= $fila['id'] ?>">
                            <div class="input-group mb-2">
                                <span class="input-group-text">Nota (0-100)</span>
                                <input type="number" name="calificacion" class="form-control" value="<?= $fila['calificacion'] ?>" min="0" max="100" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="retroalimentacion" class="form-control form-control-sm" placeholder="Retroalimentación (opcional)" value="<?= $fila['comentarios'] ?>">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">Guardar Nota</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">Nadie ha entregado esta tarea todavía.</div>
    <?php endif; ?>
</div>

<?php require '../../includes/footer.php'; ?>