<?php
// home/materias/catalogo.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// Seguridad: Solo estudiantes
if ($_SESSION['rol_id'] != 3) {
    header("Location: ../index.php");
    exit;
}

$alumno_id = $_SESSION['usuario_id'];

// Consulta Avanzada (LEFT JOIN)
// Traemos TODAS las materias y revisamos si este alumno ya tiene una inscripción en ellas
$sql = "SELECT m.id, m.nombre, m.descripcion, u.nombre as profe_nombre, u.apellidos as profe_apellido,
               i.estado as estado_inscripcion
        FROM materias m
        JOIN usuarios u ON m.maestro_id = u.id
        LEFT JOIN inscripciones i ON m.id = i.materia_id AND i.alumno_id = :alumno_id";

$stmt = $conn->prepare($sql);
$stmt->execute([':alumno_id' => $alumno_id]);
$materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

require '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h2>📖 Catálogo de Materias</h2>
        <p class="text-muted">Solicita inscripción a las clases que te interesen.</p>
    </div>
</div>

<?php if(isset($_GET['mensaje'])): ?>
    <div class="alert alert-success"> Solicitud enviada correctamente. Espera a que el maestro te acepte.</div>
<?php endif; ?>

<div class="row">
    <?php foreach ($materias as $materia): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-primary"><?= limpiar($materia['nombre']) ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        👨‍🏫 Prof. <?= limpiar($materia['profe_nombre'] . " " . $materia['profe_apellido']) ?>
                    </h6>
                    <p class="card-text"><?= limpiar($materia['descripcion']) ?></p>
                    
                    <div class="d-grid mt-3">
                        <?php if ($materia['estado_inscripcion'] == 'Aprobado'): ?>
                            <button class="btn btn-success disabled">✅ Ya estás inscrito</button>
                        
                        <?php elseif ($materia['estado_inscripcion'] == 'Pendiente'): ?>
                            <button class="btn btn-warning disabled">⏳ Solicitud Pendiente</button>
                        
                        <?php elseif ($materia['estado_inscripcion'] == 'Rechazado'): ?>
                            <button class="btn btn-danger disabled">❌ Solicitud Rechazada</button>
                        
                        <?php else: ?>
                            <form action="solicitar.php" method="POST">
                                <input type="hidden" name="materia_id" value="<?= $materia['id'] ?>">
                                <button type="submit" class="btn btn-outline-primary w-100">Inscribirse</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require '../../includes/footer.php'; ?>