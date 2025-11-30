<?php
// home/materias/ver_materia.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_referencia = $_GET['id']; // Puede ser ID de materia (Maestro) o ID de inscripción (Alumno)
$rol = $_SESSION['rol_id'];
$usuario_id = $_SESSION['usuario_id'];

$materia = null;
$es_maestro_dueno = false;
$es_alumno_inscrito = false;

// LOGICA DE PERMISOS (CRUCIAL)
if ($rol == 2) { // MAESTRO
    // Verificamos si la materia es suya
    $sql = "SELECT * FROM materias WHERE id = :id AND maestro_id = :profe";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id_referencia, ':profe' => $usuario_id]);
    $materia = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($materia) {
        $es_maestro_dueno = true;
        $materia_id = $materia['id'];
    }

} elseif ($rol == 3) { // ALUMNO
    // Verificamos si la inscripción es válida y aprobada
    $sql = "SELECT m.*, i.estado 
            FROM inscripciones i
            JOIN materias m ON i.materia_id = m.id
            WHERE i.id = :id AND i.alumno_id = :alu AND i.estado = 'Aprobado'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id_referencia, ':alu' => $usuario_id]);
    $materia = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($materia) {
        $es_alumno_inscrito = true;
        $materia_id = $materia['id'];
    }
}

// Si no tiene permiso, va para afuera
if (!$es_maestro_dueno && !$es_alumno_inscrito) {
    header("Location: ../index.php"); // O a una página de error 403
    exit;
}

// --- CONSULTA DE TAREAS ---
// Traemos las tareas de esta materia
$sql_tareas = "SELECT * FROM tareas WHERE materia_id = :mat ORDER BY fecha_limite ASC";
$stmt = $conn->prepare($sql_tareas);
$stmt->execute([':mat' => $materia_id]);
$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

require '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="text-primary">📘 <?= limpiar($materia['nombre']) ?></h2>
        <p class="text-muted"><?= limpiar($materia['descripcion']) ?></p>
    </div>
    <div class="col-md-4 text-end">
        <?php if ($es_maestro_dueno): ?>
            <a href="crear_tarea.php?id=<?= $materia_id ?>" class="btn btn-success">+ Nueva Tarea</a>
            <a href="calificaciones.php?id=<?= $materia_id ?>" class="btn btn-outline-primary">📊 Calificaciones</a>
        <?php endif; ?>
        
        <?php if ($es_alumno_inscrito): ?>
             <span class="badge bg-success p-2">Alumno Inscrito</span>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">📅 Actividades y Tareas</h5>
            </div>
            <div class="card-body">
                <?php if (count($tareas) > 0): ?>
                    <div class="list-group">
                        <?php foreach ($tareas as $tarea): ?>
                            <?php if ($es_maestro_dueno): ?>
    <a href="calificar.php?id=<?= $tarea['id'] ?>" class="btn btn-sm btn-warning">📝 Calificar Entregas</a>
<?php endif; ?>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1"><?= limpiar($tarea['titulo']) ?></h5>
                                    <p class="mb-1 small text-muted"><?= limpiar($tarea['descripcion']) ?></p>
                                    <small class="text-danger">Fecha límite: <?= $tarea['fecha_limite'] ?></small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-secondary mb-2">Valor: <?= $tarea['ponderacion'] ?>%</span>
                                    <br>
                                    <?php if ($es_alumno_inscrito): ?>
                                        <?php if ($es_alumno_inscrito): ?>
                                    <a href="entregar_tarea.php?id=<?= $tarea['id'] ?>" class="btn btn-sm btn-primary">📤 Entregar Tarea</a>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-light text-center">
                        No hay tareas asignadas todavía.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require '../../includes/footer.php'; ?>