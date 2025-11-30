<?php
// home/usuarios/index.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// 1. Seguridad: Solo Administradores (Rol 1)
if ($_SESSION['rol_id'] != 1) {
    header("Location: ../index.php");
    exit;
}

// 2. Consulta: Traemos usuarios junto con el nombre de su rol
$sql = "SELECT u.id, u.nombre, u.apellidos, u.email, u.fecha_registro, r.nombre as rol 
        FROM usuarios u 
        JOIN roles r ON u.rol_id = r.id 
        ORDER BY u.id DESC";
$stmt = $conn->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

require '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2>👥 Gestión de Usuarios</h2>
        <p class="text-muted">Administra el acceso de Maestros y Estudiantes.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="registrar.php" class="btn btn-primary">➕ Nuevo Usuario</a>
    </div>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usr): ?>
                        <tr>
                            <td><?= limpiar($usr['nombre'] . " " . $usr['apellidos']) ?></td>
                            <td><?= limpiar($usr['email']) ?></td>
                            <td>
                                <?php if($usr['rol'] == 'Administrador'): ?>
                                    <span class="badge bg-dark">Admin</span>
                                <?php elseif($usr['rol'] == 'Maestro'): ?>
                                    <span class="badge bg-primary">Maestro</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Estudiante</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted"><?= date('d/m/Y', strtotime($usr['fecha_registro'])) ?></td>
                            <td>
                                <a href="editar.php?id=<?= $usr['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                                
                                <?php if($usr['id'] != $_SESSION['usuario_id']): ?>
                                    <a href="eliminar.php?id=<?= $usr['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Seguro que deseas eliminar a este usuario? Se borrarán sus datos relacionados.');">
                                       🗑️
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../../includes/footer.php'; ?>