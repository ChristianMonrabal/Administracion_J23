<?php
// Inicia una sesión o reanuda la existente para gestionar variables de sesión.
session_start();

// Verifica si el usuario ha iniciado sesión y si es Administrador.
// Si no cumple con estas condiciones, lo redirige a la página de inicio de sesión.
if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php"); // Redirige al usuario a la página de inicio de sesión.
    exit(); // Detiene la ejecución del script.
}

// Incluye el archivo que establece la conexión con la base de datos.
include("../db/conexion.php");

// Inicializa variables para almacenar errores, mensajes de éxito y datos de notas.
$errores = ""; // Almacenará errores del formulario.
$mensaje_exito = ""; // Almacenará mensajes de éxito.
$notas = []; // Almacena las notas existentes del alumno.
$asignaturasDisponibles = []; // Almacena las asignaturas que no tienen nota asignada para el alumno.
$id_alumno = $_POST['id_alumno'] ?? null; // Obtiene el ID del alumno del formulario, si está disponible.

// Procesa el formulario solo si se ha enviado con el método POST y el botón 'guardar_notas' está presente.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_notas'])) {
    // Verifica que el ID del alumno sea válido y numérico.
    if (empty($id_alumno) || !is_numeric($id_alumno)) {
        $errores .= "Error: ID del alumno no válido.<br>"; // Agrega un error si el ID no es válido.
    } else {
        $notas = $_POST['notas'] ?? []; // Obtiene las notas enviadas en el formulario.

        // Valida cada nota enviada para las asignaturas existentes.
        foreach ($notas as $asignatura => $nota) {
            if (empty($nota) || !is_numeric($nota)) {
                $errores .= "La nota para la asignatura debe ser un número.<br>";
            } elseif ($nota < 0 || $nota > 10) {
                $errores .= "La nota para la asignatura debe estar entre 0 y 10.<br>";
            }
        }

        // Valida la asignatura y nota nuevas, si se proporcionaron.
        if (!empty($_POST['nueva_asignatura']) && isset($_POST['nota_nueva'])) {
            $nueva_asignatura = $_POST['nueva_asignatura']; // Obtiene el ID de la nueva asignatura.
            $nota_nueva = $_POST['nota_nueva']; // Obtiene la nueva nota.

            if (!is_numeric($nota_nueva)) {
                $errores .= "La nueva nota debe ser un número.<br>";
            } elseif ($nota_nueva < 0 || $nota_nueva > 10) {
                $errores .= "La nueva nota debe estar entre 0 y 10.<br>";
            }
        }

        // Si no hay errores, guarda los datos en la base de datos.
        if (empty($errores)) {
            foreach ($notas as $asignatura => $nota) {
                // Comprueba si ya existe una nota para la asignatura.
                $checkNotaQuery = "SELECT id_nota FROM notas WHERE id_alumno = ? AND id_asignatura = ?";
                $stmt = mysqli_prepare($conn, $checkNotaQuery);
                mysqli_stmt_bind_param($stmt, "ii", $id_alumno, $asignatura);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    // Si la nota existe, se actualiza.
                    $updateNotaQuery = "UPDATE notas SET nota = ? WHERE id_alumno = ? AND id_asignatura = ?";
                    $updateStmt = mysqli_prepare($conn, $updateNotaQuery);
                    mysqli_stmt_bind_param($updateStmt, "dii", $nota, $id_alumno, $asignatura);
                    mysqli_stmt_execute($updateStmt);
                    mysqli_stmt_close($updateStmt);
                } else {
                    // Si no existe, se inserta una nueva.
                    $insertNotaQuery = "INSERT INTO notas (id_alumno, id_asignatura, nota) VALUES (?, ?, ?)";
                    $insertStmt = mysqli_prepare($conn, $insertNotaQuery);
                    mysqli_stmt_bind_param($insertStmt, "iid", $id_alumno, $asignatura, $nota);
                    mysqli_stmt_execute($insertStmt);
                    mysqli_stmt_close($insertStmt);
                }
                mysqli_stmt_close($stmt);
            }

            // Inserta la nueva asignatura y nota, si se proporcionaron.
            if (!empty($_POST['nueva_asignatura']) && !empty($_POST['nota_nueva'])) {
                $insertNuevaNotaQuery = "INSERT INTO notas (id_alumno, id_asignatura, nota) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insertNuevaNotaQuery);
                mysqli_stmt_bind_param($stmt, "iid", $id_alumno, $nueva_asignatura, $nota_nueva);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }

            $mensaje_exito = "Notas guardadas correctamente."; // Mensaje de éxito al guardar las notas.
        }
    }
}

// Si se proporciona el ID del alumno, se obtienen sus datos y asignaturas.
if ($id_alumno) {
    // Obtiene el nombre y apellido del alumno.
    $queryAlumno = "SELECT nombre_alumno, apellido_alumno FROM alumnos WHERE id_alumno = ?";
    $stmtAlumno = mysqli_prepare($conn, $queryAlumno);
    mysqli_stmt_bind_param($stmtAlumno, "i", $id_alumno);
    mysqli_stmt_execute($stmtAlumno);
    mysqli_stmt_bind_result($stmtAlumno, $nombre, $apellido);
    mysqli_stmt_fetch($stmtAlumno);
    mysqli_stmt_close($stmtAlumno);

    // Obtiene las asignaturas y notas del alumno.
    $queryNotas = "SELECT n.id_asignatura, a.nombre_asignatura, n.nota FROM notas n 
                    JOIN asignaturas a ON n.id_asignatura = a.id_asignatura 
                    WHERE n.id_alumno = ?";
    $stmtNotas = mysqli_prepare($conn, $queryNotas);
    mysqli_stmt_bind_param($stmtNotas, "i", $id_alumno);
    mysqli_stmt_execute($stmtNotas);
    $notasResult = mysqli_stmt_get_result($stmtNotas);
    $notas = mysqli_fetch_all($notasResult, MYSQLI_ASSOC); // Convierte el resultado en un arreglo asociativo.
    mysqli_stmt_close($stmtNotas);

    // Obtiene las asignaturas que aún no tienen nota asignada.
    $queryAsignaturasDisponibles = "SELECT id_asignatura, nombre_asignatura FROM asignaturas 
                                    WHERE id_asignatura NOT IN (
                                        SELECT id_asignatura FROM notas WHERE id_alumno = ?
                                    )";
    $stmtAsignaturas = mysqli_prepare($conn, $queryAsignaturasDisponibles);
    mysqli_stmt_bind_param($stmtAsignaturas, "i", $id_alumno);
    mysqli_stmt_execute($stmtAsignaturas);
    $resultAsignaturas = mysqli_stmt_get_result($stmtAsignaturas);
    $asignaturasDisponibles = mysqli_fetch_all($resultAsignaturas, MYSQLI_ASSOC); // Convierte en arreglo asociativo.
    mysqli_stmt_close($stmtAsignaturas);
}
?>
