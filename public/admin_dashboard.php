<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");
include("../private/select_dashboard.php");
include("../private/show_data_alumno.php");
include_once "../private/sweet_alert.php";

$filtro_cursos = "SELECT id_curso, nombre_curso FROM cursos";
$resultCursos = mysqli_query($conn, $filtro_cursos);

// Mostrar errores y datos del formulario anterior si existen
$errors = $_SESSION['errors'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['errors'], $_SESSION['form_data']);

$hayFiltros = !empty($apellido) || !empty($curso);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Administración J23</a>
        <form class="form-inline ml-3" method="GET" action="admin_dashboard.php">
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar por Apellido" name="apellido" value="<?php echo htmlspecialchars($_GET['apellido'] ?? ''); ?>" aria-label="Buscar">
            <select class="form-control mx-2" name="curso" aria-label="Seleccionar curso">
                <option value="">Seleccionar curso</option>
                <?php while ($curso = mysqli_fetch_assoc($resultCursos)): ?>
                    <option value="<?php echo $curso['id_curso']; ?>" <?php echo (isset($_GET['curso']) && $_GET['curso'] == $curso['id_curso']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($curso['nombre_curso']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Filtrar</button>
            <?php if (!empty($_GET['apellido']) || !empty($_GET['curso'])): ?>
                <a href="admin_dashboard.php" class="btn btn-outline-danger my-2 my-sm-0 ml-2">Borrar Filtros</a>
            <?php endif; ?>
        </form>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#crearAlumnoModal">Altas</button>
                </li>
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

    <!-- Modal para Crear Alumno -->
    <div class="modal fade" 
        id="crearAlumnoModal" 
        tabindex="-1" 
        role="dialog" 
        aria-labelledby="crearAlumnoModalLabel" 
        aria-hidden="true" 
        data-open="<?php echo !empty($errors) ? 'true' : 'false'; ?>">

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
                            <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($form_data['nombre'] ?? ''); ?>">
                            <?php if (isset($errors['nombre'])): ?>
                                <small class="text-danger"><?php echo $errors['nombre']; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" name="apellido" id="apellido" class="form-control" value="<?php echo htmlspecialchars($form_data['apellido'] ?? ''); ?>">
                            <?php if (isset($errors['apellido'])): ?>
                                <small class="text-danger"><?php echo $errors['apellido']; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="email" name="correo" id="correo" class="form-control" value="<?php echo htmlspecialchars($form_data['correo'] ?? ''); ?>">
                            <?php if (isset($errors['correo'])): ?>
                                <small class="text-danger"><?php echo $errors['correo']; ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="curso">Curso:</label>
                            <select name="curso" id="curso" class="form-control">
                                <?php
                                $cursosQuery = "SELECT id_curso, nombre_curso FROM cursos";
                                $cursosResult = mysqli_query($conn, $cursosQuery);
                                while ($curso = mysqli_fetch_assoc($cursosResult)) {
                                    $selected = isset($form_data['curso']) && $form_data['curso'] == $curso['id_curso'] ? 'selected' : '';
                                    echo "<option value='{$curso['id_curso']}' $selected>{$curso['nombre_curso']}</option>";
                                }
                                ?>
                            </select>
                            <?php if (isset($errors['curso'])): ?>
                                <small class="text-danger"><?php echo $errors['curso']; ?></small>
                            <?php endif; ?>
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
                <?php 
                // Verificar si el resultado tiene registros
                if (mysqli_num_rows($result) > 0): 
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($row['apellido_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($row['correo_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_curso']); ?></td>
                            <td>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id_alumno" value="<?php echo $row['id_alumno']; ?>">
                                    <button type="submit" class="btn btn-warning btn-sm" name="editar_alumno" data-toggle="modal" data-target="#editarAlumnoModal">Editar</button>
                                </form>

                                <form method="POST" action="../private/delete_alumno.php" style="display:inline;" onsubmit="return confirmarEliminacion(event, this);">
                                    <input type="hidden" name="id_alumno" value="<?php echo $row['id_alumno']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>

                                <form method="POST" action="editar_notas.php" style="display:inline;">
                                    <input type="hidden" name="id_alumno" value="<?php echo $row['id_alumno']; ?>">
                                    <button type="submit" class="btn btn-info btn-sm">Ver/Editar Notas</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; 
                        else: ?>
                    <tr>
                        <td colspan="5">No se encontraron resultados para los filtros aplicados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Modal para Editar Alumno -->
<div class="modal fade" id="editarAlumnoModal" tabindex="-1" role="dialog" aria-labelledby="editarAlumnoModalLabel" aria-hidden="true" 
    data-open="<?php echo !empty($alumnoSeleccionado) ? 'true' : 'false'; ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarAlumnoModalLabel">Editar Alumno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="../private/update_alumno.php">
                <div class="modal-body">
                    <input type="hidden" name="id_alumno" value="<?php echo $alumnoSeleccionado['id_alumno'] ?? ''; ?>">
                    <div class="form-group">
                        <label for="editar_nombre">Nombre:</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            id="editar_nombre" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($alumnoSeleccionado['nombre_alumno'] ?? ''); ?>" 
                            required>
                    </div>
                    <div class="form-group">
                        <label for="editar_apellido">Apellido:</label>
                        <input 
                            type="text" 
                            name="apellido" 
                            id="editar_apellido" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($alumnoSeleccionado['apellido_alumno'] ?? ''); ?>" 
                            required>
                    </div>
                    <div class="form-group">
                        <label for="editar_correo">Correo:</label>
                        <input 
                            type="email" 
                            name="correo" 
                            id="editar_correo" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($alumnoSeleccionado['correo_alumno'] ?? ''); ?>" 
                            required>
                    </div>
                    <div class="form-group">
                        <label for="editar_curso">Curso:</label>
                        <select name="curso" id="editar_curso" class="form-control" required>
                            <?php
                            $cursosQuery = "SELECT id_curso, nombre_curso FROM cursos";
                            $cursosResult = mysqli_query($conn, $cursosQuery);
                            while ($curso = mysqli_fetch_assoc($cursosResult)) {
                                $selected = isset($alumnoSeleccionado['id_curso']) && $alumnoSeleccionado['id_curso'] == $curso['id_curso'] ? 'selected' : '';
                                echo "<option value='{$curso['id_curso']}' $selected>{$curso['nombre_curso']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" name="guardar_cambios" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Este bloque solo se mostrará si no hay filtros activos -->
<?php if (!$hayFiltros): ?>
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
<?php endif; ?>

<!-- Paginación -->
<?php if (!$hayFiltros): ?>
<nav aria-label="Page navigation" class="mt-3">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?limit=<?php echo $limit; ?>&page=<?php echo $page - 1; ?>" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?limit=<?php echo $limit; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?limit=<?php echo $limit; ?>&page=<?php echo $page + 1; ?>" aria-label="Siguiente">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/edit_modal.js"></script>
    <script src="../js/create_modal.js"></script>
    <script src="../js/eliminar.js"></script>
</body>
</html>
