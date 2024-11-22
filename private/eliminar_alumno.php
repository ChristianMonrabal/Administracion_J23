<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_alumno'])) {
    $id_alumno = $_POST['id_alumno'];

    // Iniciar transacción
    mysqli_begin_transaction($conn);

    try {
        // Eliminar notas del alumno
        $deleteNotasQuery = "DELETE FROM notas WHERE id_alumno = ?";
        $stmt = mysqli_prepare($conn, $deleteNotasQuery);
        mysqli_stmt_bind_param($stmt, "i", $id_alumno);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Eliminar alumno
        $deleteAlumnoQuery = "DELETE FROM alumnos WHERE id_alumno = ?";
        $stmt = mysqli_prepare($conn, $deleteAlumnoQuery);
        mysqli_stmt_bind_param($stmt, "i", $id_alumno);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Confirmar transacción
        mysqli_commit($conn);

        header("Location: ./admin_dashboard.php");
        exit();
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        mysqli_rollback($conn);
        echo "Error al eliminar el alumno: " . $e->getMessage();
    }
}
?>