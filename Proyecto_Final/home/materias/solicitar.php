<?php
// home/materias/solicitar.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// Solo Estudiantes
if ($_SESSION['rol_id'] != 3) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $materia_id = $_POST['materia_id'];
    $alumno_id = $_SESSION['usuario_id'];

    // 1. Verificamos que no se haya inscrito ya (Doble seguridad)
    $check = $conn->prepare("SELECT id FROM inscripciones WHERE materia_id = :mat AND alumno_id = :alu");
    $check->execute([':mat' => $materia_id, ':alu' => $alumno_id]);

    if ($check->rowCount() == 0) {
        // 2. Insertamos la solicitud con estado 'Pendiente' (Default en BD)
        $sql = "INSERT INTO inscripciones (alumno_id, materia_id, estado) VALUES (:alu, :mat, 'Pendiente')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':alu' => $alumno_id, ':mat' => $materia_id]);
    }

    // Redirigir al catálogo con mensaje de éxito
    header("Location: catalogo.php?mensaje=1");
    exit;
} else {
    // Si intentan entrar directo sin POST
    header("Location: catalogo.php");
}
?>