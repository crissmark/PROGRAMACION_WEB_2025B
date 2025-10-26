<?php

// Encabezado HTML
echo '<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Calificaciones</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">
<div class="container mt-5">
<h1 class="text-center mb-4">Sistema de Calificaciones CUCEA </h1>';

// Definir número de alumnos
define("NUM_ALUMNOS", 5);

// Nombres y calificaciones
$estudiantes = [
    "Beatriz" => 100,
    "Diego"   => 50,
    "José"    => 80,
    "Pablo"   => 90,
    "Carlos"  => 60
];

// Variable para sumar calificaciones
$suma = 0;

// Tabla de resultados
echo '<table class="table table-bordered table-striped text-center">
<thead class="table-dark">
<tr>
<th>Nombre</th>
<th>Calificación</th>
<th>Resultado</th>
</tr>
</thead>
<tbody>';

foreach ($estudiantes as $nombre => $calificacion) {
    $suma += $calificacion;
    if ($calificacion >= 70) {
        $resultado = "<span class=\"text-success fw-bold\">Aprobado</span>";
    } else {
        $resultado = "<span class=\"text-danger fw-bold\">Reprobado</span>";
    }

    echo "<tr>
            <td>$nombre</td>
            <td>$calificacion</td>
            <td>$resultado</td>
          </tr>";
}

$promedio = $suma / NUM_ALUMNOS;

echo '</tbody></table>';

echo '<div class="alert alert-info text-center mt-4">
<h4>Promedio general del grupo: <span class="fw-bold">' . number_format($promedio, 2) . '</span></h4>
</div>';

echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>';
?>


