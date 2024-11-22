<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ./public/signin.php");
    exit();
}

include("./db/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_alumno'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $matricula = $_POST['matricula'];
    $correo = $_POST['correo'];
    $curso = $_POST['curso'];

    $insertQuery = "INSERT INTO alumnos (nombre_alumno, apellido_alumno, matricula_alumno, correo_alumno, id_curso) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $apellido, $matricula, $correo, $curso);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: admin_dashboard.php");
    exit();
}

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Número de alumnos por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "
    SELECT 
        a.id_alumno, 
        a.nombre_alumno AS nombre_usuario, 
        a.apellido_alumno AS apellido_usuario, 
        a.matricula_alumno AS username_usuario, 
        a.correo_alumno AS correo_usuario, 
        c.nombre_curso 
    FROM 
        alumnos a
    JOIN 
        cursos c ON a.id_curso = c.id_curso
    LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
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
    <div class="container mt-5">
        <!-- Tarjeta de Bienvenida -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2>Bienvenido a J23</h2>
            </div>
            <div class="card-body">
                <h5 class="card-title">Hola, <?php echo htmlspecialchars($_SESSION['nombre']) . " " . htmlspecialchars($_SESSION['apellido']); ?>!</h5>
                <p class="card-text">Tu rol es: <strong><?php echo htmlspecialchars($_SESSION['userType']); ?></strong></p>
                <form action="./private/logout.php" method="POST" class="mt-3">
                    <button type="submit" class="btn btn-danger">Cerrar sesión</button>
                </form>
            </div>
        </div>
        <!-- Fin de la Tarjeta de Bienvenida -->

        <!-- Formulario para Crear Alumno -->
        <form method="POST" action="admin_dashboard.php" class="mb-4">
            <h3>Crear Alumno</h3>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" id="apellido" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="matricula">Matrícula:</label>
                <input type="text" name="matricula" id="matricula" class="form-control" required>
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
            <button type="submit" name="crear_alumno" class="btn btn-primary">Crear Alumno</button>
        </form>
        <!-- Fin del Formulario para Crear Alumno -->

        <!-- Lista de Alumnos -->
        <h2 class="mt-5">Lista de Alumnos</h2>
        <!-- Formulario para Seleccionar Límites de Alumnos por Página -->
        <form method="GET" action="" class="mb-3">
            <label for="limit">Alumnos por página:</label>
            <select name="limit" id="limit" onchange="this.form.submit()">
                <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
                <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
            </select>
        </form>
        <!-- Fin del Formulario para Seleccionar Límites -->

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Matrícula</th>
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
                    <td><?php echo htmlspecialchars($row['username_usuario']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_curso']); ?></td>
                    <td>
                        <!-- Formulario para Editar Alumno -->
                        <form method="POST" action="./private/editar_alumno.php" style="display:inline;">
                            <input type="hidden" name="id_alumno" value="<?php echo $row['id_alumno']; ?>">
                            <button type="submit" class="btn btn-warning btn-sm">Editar</button>
                        </form> 

                        <!-- Formulario para Eliminar Alumno -->
                        <form method="POST" action="./private/eliminar_alumno.php" style="display:inline;">
                            <input type="hidden" name="id_alumno" value="<?php echo $row['id_alumno']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>

                        <!-- Formulario para Ver/Editar Notas -->
                        <form method="POST" action="./private/editar_notas.php" style="display:inline;">
                            <input type="hidden" name="id_alumno" value="<?php echo $row['id_alumno']; ?>">
                            <button type="submit" class="btn btn-info btn-sm">Ver/Editar Notas</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Fin de la Lista de Alumnos -->
    </div>
</body>
</html>
