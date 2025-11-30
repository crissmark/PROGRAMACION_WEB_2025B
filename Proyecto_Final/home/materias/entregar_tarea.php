<?php
// home/materias/entregar_tarea.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// Validamos ID de tarea
if (!isset($_GET['id'])) {
    header("Location: mis_clases.php");
    exit;
}

$tarea_id = $_GET['id'];
$alumno_id = $_SESSION['usuario_id'];
$mensaje = "";

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comentarios = limpiar($_POST['comentarios']);
    
    // Manejo de Archivo (Opcional)
    $archivo_ruta = null;
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        $nombre_archivo = time() . "_" . $_FILES['archivo']['name'];
        $ruta_destino = "../../assets/uploads/" . $nombre_archivo;
        
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino)) {
            $archivo_ruta = $nombre_archivo;
        }
    }

    // Insertar entrega en BD
    $sql = "INSERT INTO entregas (tarea_id, alumno_id, archivo, comentarios) 
            VALUES (:tarea, :alumno, :archivo, :comentarios)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([
        ':tarea' => $tarea_id,
        ':alumno' => $alumno_id,
        ':archivo' => $archivo_ruta,
        ':comentarios' => $comentarios
    ])) {
        // Regresar a la materia con éxito
        // Primero obtenemos el ID de la materia para volver ahí
        $stmt_materia = $conn->query("SELECT materia_id FROM tareas WHERE id=$tarea_id");
        $mat = $stmt_materia->fetch(PDO::FETCH_ASSOC);
        header("Location: ver_materia.php?id=" . $mat['materia_id'] . "&entrega=1");
        exit;
    } else {
        $mensaje = "Error al enviar la tarea.";
    }
}

// Obtener info de la tarea para mostrarla
$stmt = $conn->prepare("SELECT titulo, descripcion FROM tareas WHERE id = :id");
$stmt->execute([':id' => $tarea_id]);
$tarea = $stmt->fetch(PDO::FETCH_ASSOC);

require '../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Entregar: <?= limpiar($tarea['titulo']) ?></h5>
            </div>
            <div class="card-body">
                <p class="text-muted"><?= limpiar($tarea['descripcion']) ?></p>
                <hr>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Subir Archivo (PDF, Word, Imagen)</label>
                        <input type="file" name="archivo" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Comentarios Adicionales</label>
                        <textarea name="comentarios" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Enviar Tarea 📤</button>
                    <a href="javascript:history.back()" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../../includes/footer.php'; ?>