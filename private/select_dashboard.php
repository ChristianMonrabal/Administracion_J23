<?php

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");

$apellido = isset($_GET['apellido']) ? $_GET['apellido'] : '';
$curso = isset($_GET['curso']) ? $_GET['curso'] : '';

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT a.id_alumno, a.nombre_alumno AS nombre_usuario, a.apellido_alumno AS apellido_usuario, a.correo_alumno AS correo_usuario, c.nombre_curso
    FROM alumnos a
    JOIN cursos c ON a.id_curso = c.id_curso";

$whereClauses = [];
if (!empty($apellido)) {
    $whereClauses[] = "a.apellido_alumno LIKE ?";
}
if (!empty($curso)) {
    $whereClauses[] = "a.id_curso = ?";
}

if (count($whereClauses) > 0) {
    $query .= " WHERE " . implode(" AND ", $whereClauses);
}

$query .= " ORDER BY a.id_alumno DESC LIMIT ? OFFSET ?";

$stmt = mysqli_prepare($conn, $query);

if (!empty($apellido) && !empty($curso)) {
    $param1 = "%$apellido%";
    mysqli_stmt_bind_param($stmt, "ssii", $param1, $curso, $limit, $offset);
} elseif (!empty($apellido)) {
    $param1 = "%$apellido%";
    mysqli_stmt_bind_param($stmt, "sii", $param1, $limit, $offset);
} elseif (!empty($curso)) {
    mysqli_stmt_bind_param($stmt, "sii", $curso, $limit, $offset);
} else {
    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
