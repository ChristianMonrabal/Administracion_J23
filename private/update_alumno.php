<?php
session_start(); // Inicia la sesión para gestionar las variables de sesión.

if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    // Verifica que el usuario esté autenticado y sea de tipo "Administrador".
    header("Location: ../public/signin.php"); // Redirige al inicio de sesión si no cumple las condiciones.
    exit(); // Detiene la ejecución del script.
}

include("../db/conexion.php"); // Incluye el archivo de conexión a la base de datos.

// Inicialización de las variables de error para cada campo.
$nombreError = '';
$apellidoError = '';
$correoError = '';
$cursoError = '';

// Verifica si la solicitud es de tipo POST y contiene el botón de guardar cambios.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_cambios'])) {
    // Recibe y limpia los datos del formulario.
    $idAlumno = intval($_POST['id_alumno']); // Convierte el ID del alumno a un entero.
    $nombre = trim($_POST['nombre']); // Elimina espacios antes y después del nombre.
    $apellido = trim($_POST['apellido']); // Elimina espacios antes y después del apellido.
    $correo = trim($_POST['correo']); // Elimina espacios antes y después del correo.
    $curso = intval($_POST['curso']); // Convierte el ID del curso a un entero.

    // Validación de los campos del formulario.
    if (empty($nombre)) {
        $nombreError = "El nombre es obligatorio."; // Error si el nombre está vacío.
    }

    if (empty($apellido)) {
        $apellidoError = "El apellido es obligatorio."; // Error si el apellido está vacío.
    }

    if (empty($correo)) {
        $correoError = "El correo es obligatorio."; // Error si el correo está vacío.
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        // Validación del formato del correo electrónico.
        $correoError = "El correo debe ser válido."; 
    } elseif (!preg_match('/@fje\.edu$/', $correo)) {
        // Verifica que el correo termine con el dominio @fje.edu.
        $correoError = "El correo debe tener el dominio @fje.edu"; 
    }

    if (empty($curso)) {
        $cursoError = "El curso es obligatorio."; // Error si el curso no está seleccionado.
    }

    // Si no hay errores, actualiza el alumno en la base de datos.
    if (empty($nombreError) && empty($apellidoError) && empty($correoError) && empty($cursoError)) {
        // Consulta SQL para actualizar los datos del alumno en la base de datos.
        $updateQuery = "UPDATE alumnos SET nombre_alumno = ?, apellido_alumno = ?, correo_alumno = ?, id_curso = ? WHERE id_alumno = ?";
        $stmt = mysqli_prepare($conn, $updateQuery); // Prepara la consulta para evitar inyecciones SQL.
        mysqli_stmt_bind_param($stmt, "sssii", $nombre, $apellido, $correo, $curso, $idAlumno); // Vincula los parámetros de la consulta.
        mysqli_stmt_execute($stmt); // Ejecuta la consulta.
        mysqli_stmt_close($stmt); // Cierra la consulta preparada.

        // Limpiar los datos del formulario y los errores de la sesión.
        unset($_SESSION['form_data']);
        unset($_SESSION['nombreError']);
        unset($_SESSION['apellidoError']);
        unset($_SESSION['correoError']);
        unset($_SESSION['cursoError']);

        header("Location: ../public/admin_dashboard.php"); // Redirige al panel de administración.
        exit(); // Detiene la ejecución del script.
    } else {
        // Si hay errores, guarda los errores y los datos del formulario en la sesión.
        $_SESSION['nombreError'] = $nombreError;
        $_SESSION['apellidoError'] = $apellidoError;
        $_SESSION['correoError'] = $correoError;
        $_SESSION['cursoError'] = $cursoError;
        $_SESSION['form_data'] = $_POST; // Guarda los datos del formulario.

        header("Location: ../public/admin_dashboard.php"); // Redirige de nuevo al panel de administración.
        exit(); // Detiene la ejecución del script.
    }
}
?>