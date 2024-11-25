<?php

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ./public/signin.php");
    exit();
}

include("../db/conexion.php");

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT a.id_alumno, a.nombre_alumno AS nombre_usuario, a.apellido_alumno AS apellido_usuario, a.correo_alumno AS correo_usuario, c.nombre_curso 
    FROM alumnos a
    JOIN cursos c ON a.id_curso = c.id_curso
    ORDER BY a.id_alumno DESC 
    LIMIT ? OFFSET ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
