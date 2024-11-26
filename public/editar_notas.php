<?php
include("../private/editar_notas_logic.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Administración J23</a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="btn btn-light"g href="notas_media.php">Notas Media</a>
                </li>
                <li class="nav-item dropdown ml-3">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo htmlspecialchars($_SESSION['nombre']) . " " . htmlspecialchars($_SESSION['apellido']); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <form action="../private/logout.php" method="POST">
                            <button type="submit" class="dropdown-item">Cerrar sesión</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <div class="container mt-5">
        <h2>Editar Notas de <?php echo htmlspecialchars($nombre . ' ' . $apellido); ?></h2>
        <form method="POST" action="../private/editar_notas_logic.php">
    <input type="hidden" name="id_alumno" value="<?php echo htmlspecialchars($id_alumno); ?>">

    <!-- Mostrar las asignaturas con notas ya registradas -->
    <?php if (count($notas) > 0): ?>
        <?php foreach ($notas as $nota): ?>
            <div class="form-group">
                <label for="nota_<?php echo htmlspecialchars($nota['id_asignatura']); ?>">
                    <?php echo htmlspecialchars($nota['nombre_asignatura']); ?>:
                </label>
                <input type="number" step="0.01" name="notas[<?php echo htmlspecialchars($nota['id_asignatura']); ?>]" 
                    id="nota_<?php echo htmlspecialchars($nota['id_asignatura']); ?>" 
                    class="form-control" 
                    value="<?php echo htmlspecialchars($nota['nota']); ?>" required>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Este alumno no tiene notas registradas.</p>
    <?php endif; ?>

    <!-- Selección de nueva asignatura -->
    <div class="form-group">
        <label for="nueva_asignatura">Añadir Nota para Nueva Asignatura:</label>
        <select name="nueva_asignatura" id="nueva_asignatura" class="form-control">
            <option value="" disabled selected>Elige la asignatura</option>
            <?php foreach ($asignaturasDisponibles as $asignatura): ?>
                <option value="<?php echo htmlspecialchars($asignatura['id_asignatura']); ?>">
                    <?php echo htmlspecialchars($asignatura['nombre_asignatura']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="number" step="0.01" max="10" name="nota_nueva" class="form-control mt-2" placeholder="Nota">
    </div>

    <button type="submit" name="guardar_notas" class="btn btn-primary">Guardar Notas</button>
</form>

    </div>
</body>
</html>