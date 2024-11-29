<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");

// Determinar cuántos alumnos hay en total
$totalQuery = "SELECT COUNT(*) as total FROM alumnos";
$whereClauses = [];
$params = [];
$types = "";

// Filtros
$apellido = isset($_GET['apellido']) ? $_GET['apellido'] : '';
$curso = isset($_GET['curso']) ? $_GET['curso'] : '';

if (!empty($apellido)) {
    $whereClauses[] = "apellido_alumno LIKE ?";
    $params[] = "%$apellido%";
    $types .= "s";
}
if (!empty($curso)) {
    $whereClauses[] = "id_curso = ?";
    $params[] = $curso;
    $types .= "s";
}

if (count($whereClauses) > 0) {
    $totalQuery .= " WHERE " . implode(" AND ", $whereClauses);
}

$stmtTotal = mysqli_prepare($conn, $totalQuery);
if (count($params) > 0) {
    mysqli_stmt_bind_param($stmtTotal, $types, ...$params);
}
mysqli_stmt_execute($stmtTotal);
$resultTotal = mysqli_stmt_get_result($stmtTotal);
$totalRow = mysqli_fetch_assoc($resultTotal);
$totalAlumnos = $totalRow['total'];

// Configuración de paginación
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; // Validar que no sea menor a 1
$totalPages = max(1, ceil($totalAlumnos / $limit)); // Evitar división por cero
$page = min($page, $totalPages); // No exceder el total de páginas
$offset = ($page - 1) * $limit;

// Consulta para obtener los alumnos paginados
$query = "SELECT a.id_alumno, a.nombre_alumno AS nombre_usuario, a.apellido_alumno AS apellido_usuario, 
                a.correo_alumno AS correo_usuario, c.nombre_curso
        FROM alumnos a
        JOIN cursos c ON a.id_curso = c.id_curso";

if (count($whereClauses) > 0) {
    // Calificar "id_curso" para evitar ambigüedad
    $whereClauses = array_map(function($clause) {
        return str_replace("id_curso", "a.id_curso", $clause);
    }, $whereClauses);

    $query .= " WHERE " . implode(" AND ", $whereClauses);
}
$query .= " ORDER BY a.id_alumno DESC LIMIT ? OFFSET ?";

$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>
