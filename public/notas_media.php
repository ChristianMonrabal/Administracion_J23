<?php
include("../private/notas_media_logic.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas Media</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php">Administración J23</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
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
    <h2>Asignatura con Nota Media Más Alta</h3>
        <p style="font-size: 1.5rem; font-weight: normal;">
            <?php if ($materiaAlta): ?>
                <?php echo htmlspecialchars($materiaAlta['asignatura']) . " - " . number_format($materiaAlta['nota_media'], 2); ?>
            <?php else: ?>
                No hay datos disponibles.
            <?php endif; ?>
        </p>
        
        <h2>Notas Media por Asignatura</h2>
        <table class="table table-striped table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Asignatura</th>
                    <th>Nota Media</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($mediaResult)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['asignatura']); ?></td>
                        <td><?php echo number_format($row['nota_media'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Mejores Notas por Asignatura</h3>
        <table class="table table-striped table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Asignatura</th>
                    <th>Alumno</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($millorsNotesResult)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['asignatura']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_alumno'] . ' ' . $row['apellido_alumno']); ?></td>
                        <td><?php echo number_format($row['nota'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 