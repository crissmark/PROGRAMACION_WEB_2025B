<?php
// home/index.php
require '../includes/conexion.php';
require '../includes/helpers.php';

verificar_sesion();
require '../includes/header.php';

$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol_id'];
?>

<div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
    <div class="container-fluid py-3">
        <h1 class="display-5 fw-bold">¡Hola, <?= limpiar($nombre) ?>! 👋</h1>
        <p class="col-md-8 fs-4">Bienvenido a tu Plataforma Académica.</p>
        
        <?php if ($rol == 1): ?>
            <p>Como <strong>Administrador</strong>, tienes el control total de usuarios y materias.</p>
            <a href="usuarios/index.php" class="btn btn-primary btn-lg">Gestionar Usuarios</a>
            
        <?php elseif ($rol == 2): ?>
            <p>Como <strong>Maestro</strong>, puedes gestionar tus clases y calificar a tus alumnos.</p>
            <div class="d-flex gap-2">
                <a href="materias/index.php" class="btn btn-primary btn-lg">Mis Materias</a>
                <a href="inscripciones/index.php" class="btn btn-outline-secondary btn-lg">Revisar Solicitudes</a>
            </div>

        <?php elseif ($rol == 3): ?>
            <p>Como <strong>Estudiante</strong>, aquí puedes ver tus tareas y calificaciones.</p>
            <div class="d-flex gap-2">
                <a href="materias/mis_clases.php" class="btn btn-success btn-lg">Ver mis Clases</a>
                <a href="materias/catalogo.php" class="btn btn-outline-primary btn-lg">Buscar Materias</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require '../includes/footer.php'; ?>