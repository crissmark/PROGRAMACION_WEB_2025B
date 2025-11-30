<?php
// home/inscripciones/index.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// 1. Seguridad: Solo Maestros
if ($_SESSION['rol_id'] != 2) {
    header("Location: ../index.php");
    exit;
}

$maestro_id = $_SESSION['usuario_id'];

// 2. Consulta Inteligente
// Traemos las inscripciones PENDIENTES de las materias que pertenecen a ESTE maestro
$sql = "SELECT i.id, i.fecha_solicitud, 
               u.nombre as alumno_nombre, u.apellidos as alumno_apellido, u.email,
               m.nombre as materia
        FROM inscripciones i
        JOIN materias m ON i.materia_id = m.id
        JOIN usuarios u ON i.alumno_id = u.id
        WHERE m.maestro_id = :id AND i.estado = 'Pendiente'
        ORDER BY i.fecha_solicitud ASC";

$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $maestro_id]);
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

require '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h2>🔔 Solicitudes de Inscripción</h2>
        <p class="text-muted">Alumnos esperando tu aprobación para entrar a clase.</p>
    </div>
</div>

<?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success">✅ Solicitud procesada correctamente.</div>
<?php endif; ?>

<div class="card shadow border-0">
    <div class="card-body">
        <?php if (count($solicitudes) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Alumno</th>
                            <th>Materia Solicitada</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $fila): ?>
                            <tr>
                                <td class="text-muted small"><?= $fila['fecha_solicitud'] ?></td>
                                <td>
                                    <strong><?= limpiar($fila['alumno_nombre'] . " " . $fila['alumno_apellido']) ?></strong><br>
                                    <small class="text-muted"><?= limpiar($fila['email']) ?></small>
                                </td>
                                <td><span class="badge bg-primary"><?= limpiar($fila['materia']) ?></span></td>
                                <td class="text-center">
                                    <a href="procesar.php?id=<?= $fila['id'] ?>&accion=Aprobado" 
                                       class="btn btn-success btn-sm me-2">
                                       ✅ Aprobar
                                    </a>
                                    
                                    <a href="procesar.php?id=<?= $fila['id'] ?>&accion=Rechazado" 
                                       class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('¿Rechazar a este alumno?');">
                                       ❌ Rechazar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-light text-center py-5">
                <h4>Todo al día 🎉</h4>
                <p class="text-muted">No tienes solicitudes pendientes por revisar.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require '../../includes/footer.php'; ?>