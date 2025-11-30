<?php
// home/usuarios/eliminar.php
require '../../includes/conexion.php';
require '../../includes/helpers.php';

verificar_sesion();

// Solo Admin puede borrar
if ($_SESSION['rol_id'] != 1) { 
    header("Location: ../index.php"); 
    exit; 
}

$id = $_GET['id'];

// Evitar que el admin se borre a sí mismo
if ($id == $_SESSION['usuario_id']) {
    header("Location: index.php");
    exit;
}

/* LIMPIEZA GENERAL 
   (Ejecutamos todo por si acaso, si no encuentra datos simplemente no borra nada, no da error)
*/

// 1. Si es ALUMNO: Borramos sus entregas y sus inscripciones
$conn->prepare("DELETE FROM entregas WHERE alumno_id = :id")->execute([':id' => $id]);
$conn->prepare("DELETE FROM inscripciones WHERE alumno_id = :id")->execute([':id' => $id]);

// 2. Si es MAESTRO: Borramos todo lo relacionado a sus materias
// A. Borramos las entregas de las tareas de sus materias
$conn->prepare("DELETE FROM entregas WHERE tarea_id IN (SELECT t.id FROM tareas t JOIN materias m ON t.materia_id = m.id WHERE m.maestro_id = :id)")->execute([':id' => $id]);

// B. Borramos las tareas de sus materias
$conn->prepare("DELETE FROM tareas WHERE materia_id IN (SELECT id FROM materias WHERE maestro_id = :id)")->execute([':id' => $id]);

// C. Borramos las inscripciones a sus materias
$conn->prepare("DELETE FROM inscripciones WHERE materia_id IN (SELECT id FROM materias WHERE maestro_id = :id)")->execute([':id' => $id]);

// D. Borramos sus materias
$conn->prepare("DELETE FROM materias WHERE maestro_id = :id")->execute([':id' => $id]);

// 3. FINALMENTE: Borramos al usuario
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = :id");

if ($stmt->execute([':id' => $id])) {
    header("Location: index.php?msg=eliminado");
} else {
    echo "Error al eliminar.";
}
?>