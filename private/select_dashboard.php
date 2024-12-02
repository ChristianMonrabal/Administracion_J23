<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}
// Comprueba si el usuario está autenticado y tiene el rol de "Administrador".
// Si no, redirige al usuario a la página de inicio de sesión y detiene la ejecución del script.

include("../db/conexion.php");
// Incluye el archivo de conexión a la base de datos para poder ejecutar consultas SQL.

// Determinar cuántos alumnos hay en total
$totalQuery = "SELECT COUNT(*) as total 
                FROM alumnos a
                JOIN cursos c ON a.id_curso = c.id_curso"; 
// Define una consulta SQL para contar el número total de alumnos.
// Se hace un `JOIN` con la tabla `cursos` para obtener los datos relacionados.

// Inicialización de variables para filtros dinámicos
$whereClauses = []; // Almacena condiciones `WHERE` adicionales.
$params = [];       // Almacena los valores de los parámetros para consultas preparadas.
$types = "";        // Define los tipos de datos de los parámetros (e.g., `s` para string, `i` para entero).

// Filtros
$apellido = isset($_GET['apellido']) ? $_GET['apellido'] : ''; // Filtro por apellido.
$curso = isset($_GET['curso']) ? $_GET['curso'] : '';          // Filtro por curso.

if (!empty($apellido)) {
    $whereClauses[] = "a.apellido_alumno LIKE ?"; 
    // Agrega una condición para buscar apellidos que coincidan parcialmente.
    $params[] = "%$apellido%"; // Usa comodines para búsqueda flexible.
    $types .= "s";            // Define el tipo de parámetro como string.
}

if (!empty($curso)) {
    $whereClauses[] = "a.id_curso = ?"; 
    // Agrega una condición para buscar alumnos por curso específico.
    $params[] = $curso;      // Agrega el valor del filtro a los parámetros.
    $types .= "s";           // Define el tipo de parámetro como string.
}

if (count($whereClauses) > 0) {
    $totalQuery .= " WHERE " . implode(" AND ", $whereClauses);
    // Si hay filtros, añade las condiciones al final de la consulta `totalQuery`.
}

$stmtTotal = mysqli_prepare($conn, $totalQuery);
// Prepara la consulta SQL para evitar inyecciones SQL.

if (count($params) > 0) {
    mysqli_stmt_bind_param($stmtTotal, $types, ...$params);
    // Asocia los parámetros con la consulta preparada.
}

mysqli_stmt_execute($stmtTotal);
// Ejecuta la consulta preparada.

$resultTotal = mysqli_stmt_get_result($stmtTotal);
// Obtiene los resultados de la consulta.

$totalRow = mysqli_fetch_assoc($resultTotal);
// Convierte el resultado en un array asociativo.

$totalAlumnos = $totalRow['total'];
// Almacena el total de alumnos calculado.

// Configuración de paginación
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
// Define el número de registros por página. Por defecto, 10.

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
// Define la página actual. Asegura que no sea menor a 1.

$totalPages = max(1, ceil($totalAlumnos / $limit));
// Calcula el número total de páginas basado en el número de alumnos y el límite por página.

$page = min($page, $totalPages);
// Ajusta la página actual para que no exceda el total de páginas.

$offset = ($page - 1) * $limit;
// Calcula el desplazamiento (`OFFSET`) para la consulta paginada.

// Consulta para obtener los alumnos paginados
$query = "SELECT a.id_alumno, a.nombre_alumno AS nombre_usuario, a.apellido_alumno AS apellido_usuario, 
                a.correo_alumno AS correo_usuario, c.nombre_curso
        FROM alumnos a
        JOIN cursos c ON a.id_curso = c.id_curso";
// Define la consulta principal para obtener los datos de los alumnos junto con su curso.

if (count($whereClauses) > 0) {
    $query .= " WHERE " . implode(" AND ", $whereClauses);
    // Si hay filtros, agrégalos a la consulta.
}

$query .= " ORDER BY a.id_alumno DESC LIMIT ? OFFSET ?";
// Ordena los resultados por ID de alumno en orden descendente.
// Aplica el límite de registros y el desplazamiento para la paginación.

$params[] = $limit;
$params[] = $offset;
// Agrega los parámetros de límite y desplazamiento a la lista.

$types .= "ii";
// Define los tipos de los parámetros como enteros (`i`).

$stmt = mysqli_prepare($conn, $query);
// Prepara la consulta para obtener los datos de los alumnos.

mysqli_stmt_bind_param($stmt, $types, ...$params);
// Asocia los parámetros con la consulta preparada.

mysqli_stmt_execute($stmt);
// Ejecuta la consulta preparada.

$result = mysqli_stmt_get_result($stmt);
// Obtiene el resultado de la consulta.
?>
