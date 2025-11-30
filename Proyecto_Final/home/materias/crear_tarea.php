<?php
// home/materias/crear_tarea.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// Solo Maestros
if ($_SESSION['rol_id'] != 2) {
    header("Location: ../index.php");
    exit;
}

$materia_id = $_GET['id'] ?? null;
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $materia_id = $_POST['materia_id'];
    $titulo = limpiar($_POST['titulo']);
    $descripcion = limpiar($_POST['descripcion']);
    $fecha = $_POST['fecha_limite'];
    $ponderacion = $_POST['ponderacion'];

    // Validación simple
    if (empty($titulo) || empty($fecha)) {
        $mensaje = "Título y fecha son obligatorios.";
    } else {
        $sql = "INSERT INTO tareas (materia_id, titulo, descripcion, fecha_limite, ponderacion) 
                VALUES (:mat, :tit, :desc, :fecha, :pond)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':mat' => $materia_id,
            ':tit' => $titulo,
            ':desc' => $descripcion,
            ':fecha' => $fecha,
            ':pond' => $ponderacion
        ]);

        header("Location: ver_materia.php?id=$materia_id");
        exit;
    }
}

require '../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Nueva Actividad</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="crear_tarea.php">
                    <input type="hidden" name="materia_id" value="<?= $materia_id ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Título de la Tarea</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción / Instrucciones</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Límite</label>
                            <input type="date" name="fecha_limite" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ponderación (%)</label>
                            <input type="number" name="ponderacion" class="form-control" placeholder="Ej: 20">
                            <div class="form-text">Cuánto vale esta tarea para la calificación final.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Publicar Tarea</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../../includes/footer.php'; ?>