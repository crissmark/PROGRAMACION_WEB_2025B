<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma Académica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="/Proyecto_Final/assets/css/style.css" rel="stylesheet">
</head>
<body >

<?php if (isset($_SESSION['usuario_id'])): ?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4">
  <div class="container">
    <a class="navbar-brand" href="/Proyecto_Final/home/index.php">🎓 Plataforma Académica</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        
        <li class="nav-item">
            <a class="nav-link" href="/Proyecto_Final/home/index.php">Inicio</a>
        </li>

        <?php if ($_SESSION['rol_id'] == 1): ?>
            <li class="nav-item"><a class="nav-link" href="/Proyecto_Final/home/usuarios/index.php">Usuarios</a></li>
            <li class="nav-item"><a class="nav-link" href="/Proyecto_Final/home/materias/index.php">Materias</a></li>
        <?php endif; ?>

        <?php if ($_SESSION['rol_id'] == 2): ?>
            
            <li class="nav-item"><a class="nav-link" href="/Proyecto_Final/home/inscripciones/index.php">Solicitudes</a></li>
        <?php endif; ?>

        <?php if ($_SESSION['rol_id'] == 3): ?>
            <li class="nav-item"><a class="nav-link" href="/Proyecto_Final/home/materias/catalogo.php">Inscribir Materias</a></li>
            <li class="nav-item"><a class="nav-link" href="/Proyecto_Final/home/materias/mis_clases.php">Mis Clases</a></li>
        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link btn btn-danger text-white btn-sm ms-3" href="/Proyecto_Final/logout.php">Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php endif; ?>

<div class="container">