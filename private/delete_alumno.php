<?php
// Inicia la sesión para manejar el estado del usuario y sus permisos.
session_start();

// Verifica si el usuario ha iniciado sesión y tiene permisos de administrador.
if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    // Redirige al formulario de inicio de sesión si no cumple los requisitos.
    header("Location: ../public/signin.php");
    exit();
}

// Incluye el archivo de conexión a la base de datos.
include("../db/conexion.php");

// Verifica si la solicitud es POST y contiene el identificador del alumno.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_alumno'])) {
    // Obtiene el ID del alumno que se desea eliminar.
    $id_alumno = $_POST['id_alumno'];

    // Desactiva el autocommit para iniciar la transacción manualmente.
    mysqli_autocommit($conn, false);

    try {
        // Define la consulta SQL para eliminar las notas relacionadas con el alumno.
        $deleteNotasQuery = "DELETE FROM notas WHERE id_alumno = ?";
        $stmt = mysqli_prepare($conn, $deleteNotasQuery);
        // Vincula el ID del alumno al parámetro de la consulta preparada.
        mysqli_stmt_bind_param($stmt, "i", $id_alumno);
        // Ejecuta la consulta para eliminar las notas.
        mysqli_stmt_execute($stmt);
        // Cierra la consulta preparada.
        mysqli_stmt_close($stmt);

        // Define la consulta SQL para eliminar al alumno de la base de datos.
        $deleteAlumnoQuery = "DELETE FROM alumnos WHERE id_alumno = ?";
        $stmt = mysqli_prepare($conn, $deleteAlumnoQuery);
        // Vincula el ID del alumno al parámetro de la consulta preparada.
        mysqli_stmt_bind_param($stmt, "i", $id_alumno);
        // Ejecuta la consulta para eliminar al alumno.
        mysqli_stmt_execute($stmt);
        // Cierra la consulta preparada.
        mysqli_stmt_close($stmt);

        // Confirma la transacción para que los cambios sean permanentes.
        mysqli_commit($conn);

        // Restablece el autocommit al estado predeterminado.
        mysqli_autocommit($conn, true);

        // Establece una variable de sesión para indicar que el alumno fue eliminado exitosamente.
        $_SESSION['usuario_eliminado'] = true;

        // Redirige al panel de administrador.
        header("Location: ../public/admin_dashboard.php");
        exit();
    } catch (Exception $e) {
        // Revierte los cambios en caso de que ocurra un error durante la transacción.
        mysqli_rollback($conn);

        // Restablece el autocommit al estado predeterminado.
        mysqli_autocommit($conn, true);

        // Establece un mensaje de error en la sesión para notificar al usuario.
        $_SESSION['error'] = "Error al eliminar el alumno: " . $e->getMessage();
        // Redirige al panel de administrador con el mensaje de error.
        header("Location: ../public/admin_dashboard.php");
        exit();
    }
}
?>