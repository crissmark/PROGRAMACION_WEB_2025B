<?php
// home/inscripciones/procesar.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// Solo Maestros
if ($_SESSION['rol_id'] != 2) {
    header("Location: ../index.php");
    exit;
}

// Validamos que vengan los datos correctos por la URL
if (isset($_GET['id']) && isset($_GET['accion'])) {
    
    $inscripcion_id = $_GET['id'];
    $accion = $_GET['accion']; // 'Aprobado' o 'Rechazado'
    $maestro_id = $_SESSION['usuario_id'];

    // VALIDACIÓN DE SEGURIDAD IMPORTANTE:
    // Verificamos que la inscripción pertenezca realmente a una materia de ESTE maestro.
    // (Para evitar que un maestro malicioso apruebe alumnos de otro maestro cambiando el ID en la URL)
    
    $sql = "SELECT i.id 
            FROM inscripciones i
            JOIN materias m ON i.materia_id = m.id
            WHERE i.id = :id AND m.maestro_id = :profe";
            
    $check = $conn->prepare($sql);
    $check->execute([':id' => $inscripcion_id, ':profe' => $maestro_id]);

    if ($check->rowCount() > 0) {
        // Si todo es legal, actualizamos el estado
        $update = $conn->prepare("UPDATE inscripciones SET estado = :accion WHERE id = :id");
        $update->execute([':accion' => $accion, ':id' => $inscripcion_id]);
        
        header("Location: index.php?msg=1");
    } else {
        die("Error de seguridad: No tienes permiso sobre esta solicitud.");
    }

} else {
    header("Location: index.php");
}
?>