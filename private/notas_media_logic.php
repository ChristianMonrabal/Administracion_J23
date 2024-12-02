<?php
session_start(); 
// Inicia una sesión en PHP. Esto permite acceder a las variables de sesión.

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}
// Comprueba si la sesión está activa y si el usuario tiene el rol de "Administrador".
// Si no cumple con estos criterios, redirige al usuario a la página de inicio de sesión
// y detiene la ejecución del script.

include("../db/conexion.php"); 
// Incluye el archivo de conexión a la base de datos. Esto permite ejecutar consultas SQL.

// Consulta para obtener la media de notas por asignatura
$mediaQuery = "
    SELECT asignaturas.nombre_asignatura AS asignatura, AVG(notas.nota) AS nota_media
    FROM notas
    JOIN asignaturas ON notas.id_asignatura = asignaturas.id_asignatura
    GROUP BY asignaturas.id_asignatura
";
// Define una consulta SQL para calcular la media de las notas por asignatura.
// Relaciona las tablas `notas` y `asignaturas` mediante la clave `id_asignatura`.
// Agrupa los resultados por asignatura (`GROUP BY`) y utiliza `AVG` para calcular la media.

$mediaResult = mysqli_query($conn, $mediaQuery);
// Ejecuta la consulta SQL definida anteriormente y almacena el resultado en `$mediaResult`.

// Consulta para obtener la asignatura con la nota media más alta
$materiaAltaQuery = "
    SELECT asignaturas.nombre_asignatura AS asignatura, AVG(notas.nota) AS nota_media
    FROM notas
    JOIN asignaturas ON notas.id_asignatura = asignaturas.id_asignatura
    GROUP BY asignaturas.id_asignatura
    ORDER BY nota_media DESC
    LIMIT 1
";
// Define una consulta SQL para identificar la asignatura con la nota media más alta.
// Ordena los resultados por la nota media en orden descendente (`ORDER BY nota_media DESC`).
// Limita los resultados a la primera fila (`LIMIT 1`), que corresponde a la asignatura con la mejor media.

$materiaAltaResult = mysqli_query($conn, $materiaAltaQuery);
// Ejecuta la consulta SQL para obtener la asignatura con la mejor nota media.

$materiaAlta = mysqli_fetch_assoc($materiaAltaResult);
// Convierte el resultado de la consulta en un array asociativo para acceder fácilmente
// al nombre de la asignatura y su nota media.

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
// Define una consulta SQL para obtener los alumnos con las mejores notas por asignatura.
// Relaciona las tablas `notas`, `asignaturas` y `alumnos` mediante sus claves correspondientes.
// Utiliza una subconsulta en el `WHERE` para identificar las notas máximas (`MAX(nota)`)
// en cada asignatura (`GROUP BY id_asignatura`).

$millorsNotesResult = mysqli_query($conn, $millorsNotesQuery);
// Ejecuta la consulta para obtener los alumnos con las mejores notas y almacena el resultado
// en `$millorsNotesResult`.
?>
