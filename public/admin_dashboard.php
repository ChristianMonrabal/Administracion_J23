<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ./public/signin.php");
    exit();
}

include("../db/conexion.php");
include("../private/select_dashboard.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="shortcut icon" href="./img/icon.png" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Administración J23</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#crearAlumnoModal">Crear Alumno</button>
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

    <div class="modal fade" id="crearAlumnoModal" tabindex="-1" role="dialog" aria-labelledby="crearAlumnoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearAlumnoModalLabel">Crear Alumno</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="../private/add_alumno.php">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" name="apellido" id="apellido" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="email" name="correo" id="correo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="curso">Curso:</label>
                            <select name="curso" id="curso" class="form-control" required>
                                <?php
                                $cursosQuery = "SELECT id_curso, nombre_curso FROM cursos";
                                $cursosResult = mysqli_query($conn, $cursosQuery);
                                while ($curso = mysqli_fetch_assoc($cursosResult)) {
                                    echo "<option value='{$curso['id_curso']}'>{$curso['nombre_curso']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" name="crear_alumno" class="btn btn-primary">Crear Alumno</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <table class="table table-striped table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Curso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($row['apellido_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($row['correo_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_curso']); ?></td>
                        <td>
                            <form method="POST" action="../private/editar_alumno.php" style="display:inline;">
                                <input type="hidden" name="id_alumno" value="<?php echo $row['id_alumno']; ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Editar</button>
                            </form> 

                            <form method="POST" action="../private/delete_alumno.php" style="display:inline;">
                                <input type="hidden" name="id_alumno" value="<?php echo $row['id_alumno']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>

                            <form method="POST" action="../private/editar_notas.php" style="display:inline;">
                                <input type="hidden" name="id_alumno" value="<?php echo $row['id_alumno']; ?>">
                                <button type="submit" class="btn btn-info btn-sm">Ver/Editar Notas</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-3">
        <div class="col-lg-6 col-md-8">
            <form method="GET" action="" class="form-inline justify-content-center">
                <label for="limit" class="mr-2 align-self-center">Alumnos por página:</label>
                <select name="limit" id="limit" class="form-control w-auto" onchange="this.form.submit()">
                    <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
                    <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                    <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
                </select>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
