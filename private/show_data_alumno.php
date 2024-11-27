<?php

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");

$alumnoSeleccionado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_alumno']) && isset($_POST['id_alumno'])) {
    $idAlumno = intval($_POST['id_alumno']);

    $query2 = "SELECT a.id_alumno, a.nombre_alumno, a.apellido_alumno, a.correo_alumno, a.id_curso, c.nombre_curso 
            FROM alumnos a 
            INNER JOIN cursos c ON a.id_curso = c.id_curso 
            WHERE a.id_alumno = ?";

    $stmt = mysqli_prepare($conn, $query2);
    mysqli_stmt_bind_param($stmt, "i", $idAlumno);
    mysqli_stmt_execute($stmt);
    $resultAlumno = mysqli_stmt_get_result($stmt);

    if ($resultAlumno && mysqli_num_rows($resultAlumno) > 0) {
        $alumnoSeleccionado = mysqli_fetch_assoc($resultAlumno);
    } else {
        echo "No se encontró un alumno con el ID especificado.";
    }

    mysqli_stmt_close($stmt);
}


?>
