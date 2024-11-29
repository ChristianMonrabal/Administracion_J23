<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");

// Variables de error individuales
$nombreError = '';
$apellidoError = '';
$correoError = '';
$cursoError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_cambios'])) {
    $idAlumno = intval($_POST['id_alumno']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $curso = intval($_POST['curso']);

    // Validación de los campos
    if (empty($nombre)) {
        $nombreError = "El nombre es obligatorio.";
    }

    if (empty($apellido)) {
        $apellidoError = "El apellido es obligatorio.";
    }

    if (empty($correo)) {
        $correoError = "El correo es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $correoError = "El correo debe ser válido.";
    } elseif (!preg_match('/@fje\.edu$/', $correo)) {
        $correoError = "El correo debe tener el dominio @fje.edu";
    }

    if (empty($curso)) {
        $cursoError = "El curso es obligatorio.";
    }

    // Si no hay errores, actualizar el alumno en la base de datos
    if (empty($nombreError) && empty($apellidoError) && empty($correoError) && empty($cursoError)) {
        $updateQuery = "UPDATE alumnos SET nombre_alumno = ?, apellido_alumno = ?, correo_alumno = ?, id_curso = ? WHERE id_alumno = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "sssii", $nombre, $apellido, $correo, $curso, $idAlumno);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Limpiar los datos del formulario y los errores de la sesión
        unset($_SESSION['form_data']);
        unset($_SESSION['nombreError']);
        unset($_SESSION['apellidoError']);
        unset($_SESSION['correoError']);
        unset($_SESSION['cursoError']);

        header("Location: ../public/admin_dashboard.php");
        exit();
    } else {
        // Redirigir con los errores a la página de administración
        $_SESSION['nombreError'] = $nombreError;
        $_SESSION['apellidoError'] = $apellidoError;
        $_SESSION['correoError'] = $correoError;
        $_SESSION['cursoError'] = $cursoError;
        $_SESSION['form_data'] = $_POST; // Guardar los datos del formulario

        header("Location: ../public/admin_dashboard.php");
        exit();
    }
}
?>
