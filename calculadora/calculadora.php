<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividad 14 - Calculadora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h1 class="h4 text-center mb-0">Calculadora Básica (Actividad 14)</h1>
                    </div>
                    <div class="card-body">
                        
                        <form action="procesar.php" method="GET">
                            
                            <div class="mb-3">
                                <label for="num1" class="form-label">Valor 1:</label>
                                <input type="text" class="form-control" name="num1" id="num1" required>
                            </div>

                            <div class="mb-3">
                                <label for="num2" class="form-label">Valor 2:</label>
                                <input type="text" class="form-control" name="num2" id="num2" required>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <p><strong>Selecciona la operación:</strong></p>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="operacion" id="opSuma" value="suma" required>
                                    <label class="form-check-label" for="opSuma">Suma (+)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="operacion" id="opResta" value="resta">
                                    <label class="form-check-label" for="opResta">Resta (-)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="operacion" id="opMul" value="mul">
                                    <label class="form-check-label" for="opMul">Multiplicación (*)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="operacion" id="opDiv" value="div">
                                    <label class="form-check-label" for="opDiv">División (/)</label>
                                </div>
                            </div>

                            <hr>

                            <button type="submit" class="btn btn-primary w-100">Calcular</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>