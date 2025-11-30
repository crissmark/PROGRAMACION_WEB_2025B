<?php
// home/materias/index.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

// 1. Seguridad: Solo usuarios logueados
verificar_sesion();

// 2. Seguridad: Solo Maestros pueden ver esto
if ($_SESSION['rol_id'] != 2) {
    // Si es alumno o admin tratando de entrar aquí, lo sacamos
    header("Location: ../index.php");
    exit;
}

// 3. Obtener el ID del maestro actual
$maestro_id = $_SESSION['usuario_id'];

// 4. Consulta: Traer solo las materias de ESTE maestro
$sql = "SELECT * FROM materias WHERE maestro_id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $maestro_id]);
$materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Incluimos el header (menú)
require '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2>-- Mis Materias--</h2>
        <p class="text-muted">Profesor.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="crear.php" class="btn btn-success">➕ Nueva Materia</a>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <?php if (count($materias) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Materia</th>
                            <th>Descripción</th>
                            <th>Clave</th> <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materias as $materia): ?>
                            <tr>
                                
                                <td><strong><a href="ver_materia.php?id=<?= $materia['id'] ?>"><?= limpiar($materia['nombre']) ?></a></strong></td>
                                <td><?= limpiar($materia['descripcion']) ?></td>
                                <td><span class="badge bg-secondary"><?= limpiar($materia['clave_acceso'] ?? 'N/A') ?></span></td>
                                <td>
                                    <a href="editar.php?id=<?= $materia['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                                    <a href="eliminar.php?id=<?= $materia['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta materia?');">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Aún no has registrado ninguna materia. ¡Crea la primera arriba! 👆
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require '../../includes/footer.php'; ?>