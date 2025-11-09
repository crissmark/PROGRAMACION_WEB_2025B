<?php
// Mostrar errores directamente en pantalla (útil para pruebas)
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Declarar el número de alumnos
define("NUM_ALUMNOS", 5);

// Array con los estudiantes y sus calificaciones
$estudiantes = [
    "Ana" => 85,
    "Luis" => 90,
    "María" => 78,
    "Carlos" => 92,
    "Lucía" => 88
];

// Calcular el promedio
$suma = 0;
foreach ($estudiantes as $nombre => $calificacion) {
    $suma += $calificacion;
}
$promedio = $suma / NUM_ALUMNOS;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calificaciones</title>
    <p><a href="/index.html">⬅ Regresar al menu</a></p>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Lista de Calificaciones</h1>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Calificación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $nombre => $calificacion): ?>
                    <tr>
                        <td><?= htmlspecialchars($nombre) ?></td>
                        <td><?= $calificacion ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4 class="text-center mt-4">
            Promedio general: <?= number_format($promedio, 2) ?>
        </h4>
    </div>
</body>
</html>
