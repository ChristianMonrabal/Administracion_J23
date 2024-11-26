<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php");
    exit();
}

include("../db/conexion.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_alumno'])) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $curso = intval($_POST['curso']);

    // Validación de los campos
    if (empty($nombre)) {
        $errors['nombre'] = "El nombre es obligatorio.";
    }

    if (empty($apellido)) {
        $errors['apellido'] = "El apellido es obligatorio.";
    }

    if (empty($correo)) {
        $errors['correo'] = "El correo es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errors['correo'] = "El correo debe ser válido.";
    } elseif (!preg_match('/@jfe\.edu$/', $correo)) {
        $errors['correo'] = "El correo debe tener el dominio @jfe.edu.";
    }

    if (empty($curso)) {
        $errors['curso'] = "El curso es obligatorio.";
    }

    // Si no hay errores, insertar el alumno en la base de datos
    if (empty($errors)) {
        $insertQuery = "INSERT INTO alumnos (nombre_alumno, apellido_alumno, correo_alumno, id_curso) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sssi", $nombre, $apellido, $correo, $curso);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: ../public/admin_dashboard.php?mensaje=Alumno creado con éxito");
        exit();
    } else {
        // Redireccionar con errores a la página de administración
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: ../public/admin_dashboard.php");
        exit();
    }
}
?>