<?php
// home/materias/calificaciones.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// Solo Maestros
if ($_SESSION['rol_id'] != 2) { header("Location: ../index.php"); exit; }

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$materia_id = $_GET['id'];
$maestro_id = $_SESSION['usuario_id'];

// Verifica que la materia sea de este maestro
$stmt = $conn->prepare("SELECT * FROM materias WHERE id = :id AND maestro_id = :profe");
$stmt->execute([':id' => $materia_id, ':profe' => $maestro_id]);
$materia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$materia) { die("Acceso denegado o materia no encontrada."); }

// Obtiene todas las tares de esta materia (Para que se pongan en las columnas de la tabla)
$stmt_tareas = $conn->prepare("SELECT id, titulo, ponderacion FROM tareas WHERE materia_id = :id ORDER BY id ASC");
$stmt_tareas->execute([':id' => $materia_id]);
$tareas = $stmt_tareas->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los alumnos aprobados 
$sql_alumnos = "SELECT u.id, u.nombre, u.apellidos, u.email 
                FROM inscripciones i
                JOIN usuarios u ON i.alumno_id = u.id
                WHERE i.materia_id = :id AND i.estado = 'Aprobado'
                ORDER BY u.apellidos ASC";
$stmt_alumnos = $conn->prepare($sql_alumnos);
$stmt_alumnos->execute([':id' => $materia_id]);
$alumnos = $stmt_alumnos->fetchAll(PDO::FETCH_ASSOC);

// Obtiene todas las calificaciones de golpe (Para no hacer mil consultas)
$sql_notas = "SELECT e.alumno_id, e.tarea_id, e.calificacion 
              FROM entregas e
              JOIN tareas t ON e.tarea_id = t.id
              WHERE t.materia_id = :id";
$stmt_notas = $conn->prepare($sql_notas);
$stmt_notas->execute([':id' => $materia_id]);
$notas_raw = $stmt_notas->fetchAll(PDO::FETCH_ASSOC);

// Organizamos las notas en un arreglo fácil de usar: $mapa_notas[alumno_id][tarea_id] = calificacion
$mapa_notas = [];
foreach ($notas_raw as $nota) {
    $mapa_notas[$nota['alumno_id']][$nota['tarea_id']] = $nota['calificacion'];
}

require '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h3>📊 Reporte de Calificaciones</h3>
        <h5 class="text-muted"><?= limpiar($materia['nombre']) ?></h5>
    </div>
    <div class="col-md-4 text-end">
        <a href="ver_materia.php?id=<?= $materia_id ?>" class="btn btn-secondary">⬅ Volver a la Materia</a>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <?php if (count($alumnos) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start">Alumno</th>
                            <?php foreach ($tareas as $tarea): ?>
                                <th>
                                    <?= limpiar($tarea['titulo']) ?>
                                    <br><span class="badge bg-secondary" style="font-size:0.7em"><?= $tarea['ponderacion'] ?>%</span>
                                </th>
                            <?php endforeach; ?>
                            <th class="bg-primary">Promedio Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumnos as $alumno): ?>
                            <tr>
                                <td class="text-start fw-bold">
                                    <?= limpiar($alumno['apellidos'] . " " . $alumno['nombre']) ?>
                                </td>

                                <?php 
                                $promedio_acumulado = 0;
                                $suma_ponderacion = 0;
                                ?>

                                <?php foreach ($tareas as $tarea): ?>
                                    <?php 
                                        $nota = $mapa_notas[$alumno['id']][$tarea['id']] ?? null; 
                                        
                                        // Cálculo del promedio (Nota * Ponderacion / 100)
                                        if ($nota !== null) {
                                            $promedio_acumulado += ($nota * $tarea['ponderacion']) / 100;
                                        }
                                        $suma_ponderacion += $tarea['ponderacion'];
                                    ?>
                                    <td>
                                        <?php if ($nota !== null): ?>
                                            <span class="<?= $nota < 60 ? 'text-danger fw-bold' : 'text-dark' ?>">
                                                <?= $nota ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>

                                <td class="fw-bold fs-5 <?= $promedio_acumulado < 60 ? 'text-danger' : 'text-success' ?>">
                                    <?= number_format($promedio_acumulado, 1) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p class="text-muted small mt-2">* El promedio se calcula multiplicando la nota por su ponderación.</p>
        <?php else: ?>
            <div class="alert alert-info">No hay alumnos inscritos (y aprobados) en esta materia.</div>
        <?php endif; ?>
    </div>
</div>

<?php require '../../includes/footer.php'; ?>