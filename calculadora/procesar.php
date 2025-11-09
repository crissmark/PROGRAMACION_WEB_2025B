<?php

// --- INICIO DE LÓGICA PHP (sin cambios) ---
$num1_str = $_GET['num1'];
$num2_str = $_GET['num2'];
$op = $_GET['operacion'];
$resultado = 0;
$error_msg = ""; // Variable para guardar mensajes de error

// 2. VALIDACIÓN
if (!is_numeric($num1_str) || !is_numeric($num2_str)) {
    $error_msg = "Por favor, ingrese solo valores numéricos en los campos.";
} else {
    // 3. CONVERSIÓN
    $num1 = (float)$num1_str;
    $num2 = (float)$num2_str;

    // 4. CÁLCULO
    switch ($op) {
        case "suma":
            $resultado = $num1 + $num2;
            break;
        case "resta":
            $resultado = $num1 - $num2;
            break;
        case "mul":
            $resultado = $num1 * $num2;
            break;
        case "div":
            if ($num2 == 0) {
                $error_msg = "¡No se puede dividir por cero!";
            } else {
                $resultado = $num1 / $num2;
            }
            break;
        default:
            $error_msg = "La operación indicada no está disponible.";
    }
}
// --- FIN DE LÓGICA PHP ---
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado - Calculadora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h1 class="h4 text-center mb-0">Resultado de la Operación</h1>
                    </div>
                    <div class="card-body text-center">

                        <?php
                        // 3. Lógica para mostrar ÉXITO o ERROR
                        
                        if ($error_msg != "") {
                            // SI HUBO ERROR: Mostrar alerta de peligro
                            echo '<div class="alert alert-danger" role="alert">';
                            echo '<strong>Error:</strong> ' . $error_msg;
                            echo '</div>';
                        } else {
                            // SI HUBO ÉXITO: Mostrar alerta de éxito
                            echo '<div class="alert alert-success" role="alert">';
                            echo "<p class='mb-1'>La operación seleccionada fue: <strong>" . htmlspecialchars($op) . "</strong></p>";
                            echo "<h3 class='alert-heading'>El resultado es: $resultado</h3>";
                            echo '</div>';
                        }
                        ?>

                        <hr>
                        <a href="form.php" class="btn btn-secondary">
                            &laquo; Volver a la calculadora
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>