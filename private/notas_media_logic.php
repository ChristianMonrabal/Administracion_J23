<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");

// Consulta para obtener la media de notas por asignatura
$mediaQuery = "
    SELECT asignaturas.nombre_asignatura AS asignatura, AVG(notas.nota) AS nota_media
    FROM notas
    JOIN asignaturas ON notas.id_asignatura = asignaturas.id_asignatura
    GROUP BY asignaturas.id_asignatura
";
$mediaResult = mysqli_query($conn, $mediaQuery);

// Consulta para obtener la asignatura con la nota media más alta
$materiaAltaQuery = "
    SELECT asignaturas.nombre_asignatura AS asignatura, AVG(notas.nota) AS nota_media
    FROM notas
    JOIN asignaturas ON notas.id_asignatura = asignaturas.id_asignatura
    GROUP BY asignaturas.id_asignatura
    ORDER BY nota_media DESC
    LIMIT 1
";
$materiaAltaResult = mysqli_query($conn, $materiaAltaQuery);
$materiaAlta = mysqli_fetch_assoc($materiaAltaResult);

// Consulta para obtener los alumnos con las mejores notas por asignatura
$millorsNotesQuery = "
    SELECT asignaturas.nombre_asignatura AS asignatura, 
        alumnos.nombre_alumno, 
        alumnos.apellido_alumno, 
        notas.nota
    FROM notas
    JOIN asignaturas ON notas.id_asignatura = asignaturas.id_asignatura
    JOIN alumnos ON notas.id_alumno = alumnos.id_alumno
    WHERE (notas.id_asignatura, notas.nota) IN (
        SELECT id_asignatura, MAX(nota)
        FROM notas
        GROUP BY id_asignatura
    )
";
$millorsNotesResult = mysqli_query($conn, $millorsNotesQuery);
?>