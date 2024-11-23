<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");

// Verificar si se está intentando guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_alumno'])) {
    // Validar entrada
    $id_alumno = $_POST['id_alumno'];
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $matricula = trim($_POST['matricula']);
    $correo = trim($_POST['correo']);
    $curso = $_POST['curso'];

    if (!empty($id_alumno) && !empty($nombre) && !empty($apellido) && !empty($matricula) && !empty($correo) && !empty($curso)) {
        // Realizar la actualización
        $updateQuery = "UPDATE alumnos 
                        SET nombre_alumno = ?, apellido_alumno = ?, matricula_alumno = ?, correo_alumno = ?, id_curso = ? 
                        WHERE id_alumno = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ssssii", $nombre, $apellido, $matricula, $correo, $curso, $id_alumno);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: ../admin_dashboard.php");
            exit();
        } else {
            echo "Error al actualizar el alumno: " . mysqli_error($conn);
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_alumno'])) {
    // Obtener datos del alumno para mostrar en el formulario
    $id_alumno = $_POST['id_alumno'];
    $query = "SELECT nombre_alumno, apellido_alumno, matricula_alumno, correo_alumno, id_curso 
            FROM alumnos 
            WHERE id_alumno = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_alumno);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nombre, $apellido, $matricula, $correo, $id_curso);
    if (!mysqli_stmt_fetch($stmt)) {
        header("Location: ../admin_dashboard.php");
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: ../admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Alumno</h2>
        <form method="POST" action="editar_alumno.php">
            <input type="hidden" name="id_alumno" value="<?php echo htmlspecialchars($id_alumno); ?>">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($nombre); ?>" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" id="apellido" class="form-control" value="<?php echo htmlspecialchars($apellido); ?>" required>
            </div>
            <div class="form-group">
                <label for="matricula">Matrícula:</label>
                <input type="text" name="matricula" id="matricula" class="form-control" value="<?php echo htmlspecialchars($matricula); ?>" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" name="correo" id="correo" class="form-control" value="<?php echo htmlspecialchars($correo); ?>" required>
            </div>
            <div class="form-group">
                <label for="curso">Curso:</label>
                <select name="curso" id="curso" class="form-control" required>
                    <?php
                    $cursosQuery = "SELECT id_curso, nombre_curso FROM cursos";
                    $cursosResult = mysqli_query($conn, $cursosQuery);
                    while ($curso = mysqli_fetch_assoc($cursosResult)) {
                        $selected = $curso['id_curso'] == $id_curso ? 'selected' : '';
                        echo "<option value='{$curso['id_curso']}' $selected>{$curso['nombre_curso']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="editar_alumno" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
