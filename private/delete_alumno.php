<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_alumno'])) {
    $id_alumno = $_POST['id_alumno'];

    mysqli_begin_transaction($conn);

    try {
        $deleteNotasQuery = "DELETE FROM notas WHERE id_alumno = ?";
        $stmt = mysqli_prepare($conn, $deleteNotasQuery);
        mysqli_stmt_bind_param($stmt, "i", $id_alumno);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $deleteAlumnoQuery = "DELETE FROM alumnos WHERE id_alumno = ?";
        $stmt = mysqli_prepare($conn, $deleteAlumnoQuery);
        mysqli_stmt_bind_param($stmt, "i", $id_alumno);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);

        $_SESSION['usuario_eliminado'] = true;

        header("Location: ../public/admin_dashboard.php");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error'] = "Error al eliminar el alumno: " . $e->getMessage();
        header("Location: ../public/admin_dashboard.php");
        exit();
    }
}
?>