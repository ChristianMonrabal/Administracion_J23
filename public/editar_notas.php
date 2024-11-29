<?php include("../private/editar_notas_logic.php"); ?>

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
        <div class="container-fluid"><a class="navbar-brand" href="admin_dashboard.php">Administración J23</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="btn btn-light" href="notas_media.php">Notas Media</a></li>
                    <li class="nav-item dropdown ml-3"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') . " " . htmlspecialchars($_SESSION['apellido'] ?? ''); ?></a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <form action="../private/logout.php" method="POST"><button type="submit" class="dropdown-item">Cerrar sesión</button></form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Editar Notas de <?php echo htmlspecialchars($nombre ?? '') . ' ' . htmlspecialchars($apellido ?? ''); ?></h2>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger"><?php echo $errores; ?></div>
        <?php endif; ?>

        <?php if (!empty($mensaje_exito)): ?>
            <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="id_alumno" value="<?php echo htmlspecialchars($id_alumno); ?>">

            <?php foreach ($notas as $nota): ?>
                <div class="form-group">
                    <label><?php echo htmlspecialchars($nota['nombre_asignatura'] ?? ''); ?></label>
                    <input type="number" step="0.01" name="notas[<?php echo htmlspecialchars($nota['id_asignatura']); ?>]"
                        value="<?php echo htmlspecialchars($nota['nota']); ?>" class="form-control">
                </div>
            <?php endforeach; ?>

            <div class="form-group">
                <label>Añadir Nota para Nueva Asignatura:</label>
                <select name="nueva_asignatura" class="form-control">
                    <option value="" disabled selected>Seleccione</option>
                    <?php foreach ($asignaturasDisponibles as $asignatura): ?>
                        <option value="<?php echo htmlspecialchars($asignatura['id_asignatura']); ?>">
                            <?php echo htmlspecialchars($asignatura['nombre_asignatura']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="number" step="0.01" name="nota_nueva" class="form-control mt-2" placeholder="Nota">
            </div>

            <button type="submit" name="guardar_notas" class="btn btn-primary">Guardar Notas</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>A 
</body>
</html>