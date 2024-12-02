<?php

// Verifica si el usuario está autenticado y es de tipo "Administrador".
if (!isset($_SESSION['loggedin']) || $_SESSION['userType'] !== 'Administrador') {
    header("Location: ../public/signin.php"); // Redirige al inicio de sesión si no cumple las condiciones.
    exit(); // Detiene la ejecución del script.
}

include("../db/conexion.php"); // Incluye el archivo de conexión a la base de datos.

// Inicializa la variable para almacenar los datos del alumno seleccionado.
$alumnoSeleccionado = null;

// Verifica si la solicitud es de tipo POST y contiene los datos necesarios para editar un alumno.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_alumno']) && isset($_POST['id_alumno'])) {
    $idAlumno = intval($_POST['id_alumno']); 
    // Convierte el ID del alumno recibido en un entero para mayor seguridad.

    // Consulta SQL para obtener los datos del alumno seleccionado, junto con el nombre del curso asociado.
    $query2 = "SELECT a.id_alumno, a.nombre_alumno, a.apellido_alumno, a.correo_alumno, a.id_curso, c.nombre_curso 
            FROM alumnos a 
            INNER JOIN cursos c ON a.id_curso = c.id_curso 
            WHERE a.id_alumno = ?";
    // Se utiliza un JOIN para obtener información del curso asociado al alumno.

    $stmt = mysqli_prepare($conn, $query2); // Prepara la consulta para evitar inyecciones SQL.
    mysqli_stmt_bind_param($stmt, "i", $idAlumno); // Vincula el parámetro (ID del alumno) a la consulta preparada.
    mysqli_stmt_execute($stmt); // Ejecuta la consulta.
    $resultAlumno = mysqli_stmt_get_result($stmt); // Obtiene el resultado de la consulta.

    if ($resultAlumno && mysqli_num_rows($resultAlumno) > 0) {
        // Si el resultado contiene datos, convierte la primera fila en un array asociativo.
        $alumnoSeleccionado = mysqli_fetch_assoc($resultAlumno);
    } else {
        // Si no se encuentran resultados, muestra un mensaje de error.
        echo "No se encontró un alumno con el ID especificado.";
    }

    mysqli_stmt_close($stmt); // Cierra la consulta preparada.
}
?>