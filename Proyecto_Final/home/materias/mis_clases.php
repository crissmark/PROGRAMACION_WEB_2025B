<?php
// home/materias/mis_clases.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// Solo Estudiantes
if ($_SESSION['rol_id'] != 3) {
    header("Location: ../index.php");
    exit;
}

$alumno_id = $_SESSION['usuario_id'];

// Consulta: Traemos las inscripciones de este alumno con los datos de la materia y el profesor
$sql = "SELECT i.id as inscripcion_id, i.estado, i.fecha_solicitud,
               m.nombre as materia, m.descripcion, 
               u.nombre as profe_nombre, u.apellidos as profe_apellido
        FROM inscripciones i
        JOIN materias m ON i.materia_id = m.id
        JOIN usuarios u ON m.maestro_id = u.id
        WHERE i.alumno_id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $alumno_id]);
$inscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

require '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2>Bienvenido Estudiante</h2>
        <h2>¡Mis Clases!</h2>
        <p class="text-muted">Aquí puedes ver todas tus inscripciones.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="catalogo.php" class="btn btn-primary">🔍 Buscar Nuevas Materias</a>
    </div>
</div>

<div class="row">
    <?php if (count($inscripciones) > 0): ?>
        <?php foreach ($inscripciones as $fila): ?>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-start border-4 
                    <?= $fila['estado'] == 'Aprobado' ? 'border-success' : ($fila['estado'] == 'Rechazado' ? 'border-danger' : 'border-warning') ?>">
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title"><?= limpiar($fila['materia']) ?></h5>
                                <h6 class="text-muted small">Prof. <?= limpiar($fila['profe_nombre'] . " " . $fila['profe_apellido']) ?></h6>
                            </div>
                            
                            <?php if ($fila['estado'] == 'Aprobado'): ?>
                                <span class="badge bg-success">Aprobado</span>
                            <?php elseif ($fila['estado'] == 'Pendiente'): ?>
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Rechazado</span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="mt-2 text-secondary small"><?= limpiar($fila['descripcion']) ?></p>

                        <?php if ($fila['estado'] == 'Aprobado'): ?>
                            <div class="d-grid mt-3">
                                <a href="ver_materia.php?id=<?= $fila['inscripcion_id'] ?>" class="btn btn-outline-success btn-sm">
                                    Entrar al Aula ➡️
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-light border mt-2 py-1 small text-center">
                                <?php if ($fila['estado'] == 'Pendiente'): ?>
                                    ⏳ Esperando aprobación del maestro.
                                <?php else: ?>
                                    ❌ No puedes acceder a esta clase.
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <h4>Aún no tienes clases inscritas 😢</h4>
                <p>Ve al catálogo para buscar materias disponibles.</p>
                <a href="catalogo.php" class="btn btn-primary mt-2">Ir al Catálogo</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require '../../includes/footer.php'; ?>