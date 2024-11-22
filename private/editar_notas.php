<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_alumno'])) {
    $id_alumno = $_POST['id_alumno'];

    // Obtener datos del alumno
    $query = "SELECT nombre_alumno, apellido_alumno, matricula_alumno, correo_alumno, id_curso FROM alumnos WHERE id_alumno = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_alumno);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nombre, $apellido, $matricula, $correo, $id_curso);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Obtener las asignaturas y las notas del alumno
    $notasQuery = "SELECT asignatura, nota FROM notas WHERE id_alumno = ?";
    $notasStmt = mysqli_prepare($conn, $notasQuery);
    mysqli_stmt_bind_param($notasStmt, "i", $id_alumno);
    mysqli_stmt_execute($notasStmt);
    $notasResult = mysqli_stmt_get_result($notasStmt);
    
    // Verifica si hay notas y las guarda
    if (mysqli_num_rows($notasResult) > 0) {
        $notas = mysqli_fetch_all($notasResult, MYSQLI_ASSOC);
    } else {
        $notas = []; // Si no tiene notas, inicializa un arreglo vacío
    }

    mysqli_stmt_close($notasStmt);
}

// Guardar notas (cuando se hace submit del formulario)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_notas'])) {
    $notas = $_POST['notas'];
    foreach ($notas as $asignatura => $nota) {
        // Verificar si ya existe una nota para esa asignatura
        $checkNotaQuery = "SELECT id_nota FROM notas WHERE id_alumno = ? AND asignatura = ?";
        $checkStmt = mysqli_prepare($conn, $checkNotaQuery);
        mysqli_stmt_bind_param($checkStmt, "is", $id_alumno, $asignatura);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);
        
        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            // Si existe, actualizar la nota
            $updateNotaQuery = "UPDATE notas SET nota = ? WHERE id_alumno = ? AND asignatura = ?";
            $stmt = mysqli_prepare($conn, $updateNotaQuery);
            mysqli_stmt_bind_param($stmt, "dis", $nota, $id_alumno, $asignatura);
        } else {
            // Si no existe, insertar la nota
            $insertNotaQuery = "INSERT INTO notas (id_alumno, asignatura, nota) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertNotaQuery);
            mysqli_stmt_bind_param($stmt, "isd", $id_alumno, $asignatura, $nota);
        }

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_stmt_free_result($checkStmt);
    }

    header("Location: ../admin_dashboard.php");
    exit();
}
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
    <div class="container mt-5">
        <h2>Editar Notas de <?php echo htmlspecialchars($nombre . ' ' . $apellido); ?></h2>
        <form method="POST" action="editar_notas.php">
            <input type="hidden" name="id_alumno" value="<?php echo htmlspecialchars($id_alumno); ?>">
            
            <?php if (count($notas) > 0): ?>
                <?php foreach ($notas as $nota): ?>
                    <div class="form-group">
                        <label for="nota_<?php echo htmlspecialchars($nota['asignatura']); ?>"><?php echo htmlspecialchars($nota['asignatura']); ?>:</label>
                        <input type="number" step="0.01" name="notas[<?php echo htmlspecialchars($nota['asignatura']); ?>]" id="nota_<?php echo htmlspecialchars($nota['asignatura']); ?>" class="form-control" value="<?php echo htmlspecialchars($nota['nota']); ?>" required>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Este alumno no tiene notas registradas.</p>
            <?php endif; ?>

            <button type="submit" name="guardar_notas" class="btn btn-primary">Guardar Notas</button>
        </form>
    </div>
</body>
</html>
