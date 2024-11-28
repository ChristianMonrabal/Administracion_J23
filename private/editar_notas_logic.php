<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_alumno = $_POST['id_alumno'] ?? null;

    // Guardar o actualizar notas
    if (isset($_POST['guardar_notas'])) {
        $notas = $_POST['notas'] ?? [];
        foreach ($notas as $asignatura => $nota) {
            // Verificar si existe una nota para esa asignatura
            $checkNotaQuery = "SELECT id_nota FROM notas WHERE id_alumno = ? AND id_asignatura = ?";
            $stmt = mysqli_prepare($conn, $checkNotaQuery);
            mysqli_stmt_bind_param($stmt, "ii", $id_alumno, $asignatura);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Actualizar la nota
                $updateNotaQuery = "UPDATE notas SET nota = ? WHERE id_alumno = ? AND id_asignatura = ?";
                $updateStmt = mysqli_prepare($conn, $updateNotaQuery);
                mysqli_stmt_bind_param($updateStmt, "dii", $nota, $id_alumno, $asignatura);
                mysqli_stmt_execute($updateStmt);
                mysqli_stmt_close($updateStmt);
            } else {
                // Insertar nueva nota
                $insertNotaQuery = "INSERT INTO notas (id_alumno, id_asignatura, nota) VALUES (?, ?, ?)";
                $insertStmt = mysqli_prepare($conn, $insertNotaQuery);
                mysqli_stmt_bind_param($insertStmt, "iid", $id_alumno, $asignatura, $nota);
                mysqli_stmt_execute($insertStmt);
                mysqli_stmt_close($insertStmt);
            }
            mysqli_stmt_close($stmt);
        }

        // Guardar nueva asignatura y nota si se proporcionan
        if (!empty($_POST['nueva_asignatura']) && !empty($_POST['nota_nueva'])) {
            $nueva_asignatura = $_POST['nueva_asignatura'];
            $nota_nueva = $_POST['nota_nueva'];
            $insertNuevaNotaQuery = "INSERT INTO notas (id_alumno, id_asignatura, nota) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertNuevaNotaQuery);
            mysqli_stmt_bind_param($stmt, "iid", $id_alumno, $nueva_asignatura, $nota_nueva);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        header("Location: ../public/admin_dashboard.php");
        exit();
    }

    // Obtener datos del alumno
    $queryAlumno = "SELECT nombre_alumno, apellido_alumno FROM alumnos WHERE id_alumno = ?";
    $stmtAlumno = mysqli_prepare($conn, $queryAlumno);
    mysqli_stmt_bind_param($stmtAlumno, "i", $id_alumno);
    mysqli_stmt_execute($stmtAlumno);
    mysqli_stmt_bind_result($stmtAlumno, $nombre, $apellido);
    mysqli_stmt_fetch($stmtAlumno);
    mysqli_stmt_close($stmtAlumno);

    // Obtener las asignaturas y notas existentes
    $queryNotas = "SELECT n.id_asignatura, a.nombre_asignatura, n.nota FROM notas n 
                JOIN asignaturas a ON n.id_asignatura = a.id_asignatura 
                WHERE n.id_alumno = ?";
    $stmtNotas = mysqli_prepare($conn, $queryNotas);
    mysqli_stmt_bind_param($stmtNotas, "i", $id_alumno);
    mysqli_stmt_execute($stmtNotas);
    $notasResult = mysqli_stmt_get_result($stmtNotas);
    $notas = mysqli_fetch_all($notasResult, MYSQLI_ASSOC);
    mysqli_stmt_close($stmtNotas);

    // Obtener las asignaturas disponibles (sin notas registradas)
    $queryAsignaturasDisponibles = "SELECT id_asignatura, nombre_asignatura FROM asignaturas 
                                    WHERE id_asignatura NOT IN (
                                        SELECT id_asignatura FROM notas WHERE id_alumno = ?
                                    )";
    $stmtAsignaturas = mysqli_prepare($conn, $queryAsignaturasDisponibles);
    mysqli_stmt_bind_param($stmtAsignaturas, "i", $id_alumno);
    mysqli_stmt_execute($stmtAsignaturas);
    $resultAsignaturas = mysqli_stmt_get_result($stmtAsignaturas);
    $asignaturasDisponibles = mysqli_fetch_all($resultAsignaturas, MYSQLI_ASSOC);
    mysqli_stmt_close($stmtAsignaturas);
}
?>