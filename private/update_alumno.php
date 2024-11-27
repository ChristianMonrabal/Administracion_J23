<?php
// Incluir la conexión a la base de datos
require_once('../db/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_cambios'])) {
    // Sanear y validar los datos recibidos del formulario
    $idAlumno = isset($_POST['id_alumno']) ? intval($_POST['id_alumno']) : 0;
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $curso = isset($_POST['curso']) ? intval($_POST['curso']) : 0;

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($curso)) {
        // Si falta algún campo, redirigir o mostrar mensaje de error
        die("Todos los campos son obligatorios.");
    }

    // Preparar la consulta para actualizar los datos
    $query = "UPDATE alumnos 
            SET nombre_alumno = ?, apellido_alumno = ?, correo_alumno = ?, id_curso = ? 
            WHERE id_alumno = ?";

    // Usar declaración preparada para evitar inyecciones SQL
    if ($stmt = mysqli_prepare($conn, $query)) {
        // Enlazar los parámetros de la declaración
        mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $apellido, $correo, $curso, $idAlumno);

        // Ejecutar la declaración
        if (mysqli_stmt_execute($stmt)) {
            // Si se actualiza correctamente, redirigir o mostrar un mensaje de éxito
            header("Location: ../public/admin_dashboard.php");
            exit();
        } else {
            // Si ocurre un error, mostrar mensaje de error
            echo "Error al actualizar el alumno: " . mysqli_error($conn);
        }

        // Cerrar la declaración
        mysqli_stmt_close($stmt);
    } else {
        echo "Error al preparar la consulta: " . mysqli_error($conn);
    }
}

// Cerrar la conexión
mysqli_close($conn);
?>
