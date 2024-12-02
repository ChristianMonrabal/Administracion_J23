<?php
// Inicia una sesión para gestionar el estado del usuario
session_start();

// Verifica si la solicitud se realiza mediante el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene y recorta el nombre de usuario del formulario
    $username = trim($_POST['username']);
    // Obtiene y recorta la contraseña del formulario
    $password = trim($_POST['password']);
    // Sanea el nombre de usuario para evitar la inyección de caracteres especiales
    $username = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Verifica si alguno de los campos está vacío
    if (empty($username) || empty($password)) {
        // Mensaje de error si faltan campos
        $errorMessage = "Por favor, complete todos los campos.";
    } else {
        // Incluye el archivo de conexión a la base de datos
        include("../db/conexion.php");

        // Consulta SQL para buscar el usuario por su nombre de usuario
        $query = "SELECT password_usuario, tipo_usuario, nombre_usuario, apellido_usuario FROM usuarios WHERE username_usuario = ?";
        // Prepara la consulta para evitar inyección SQL
        $stmt = mysqli_prepare($conn, $query);

        // Verifica si la consulta fue preparada correctamente
        if ($stmt) {
            // Asocia los parámetros a la consulta preparada
            mysqli_stmt_bind_param($stmt, "s", $username);
            // Ejecuta la consulta
            mysqli_stmt_execute($stmt);
            // Almacena el resultado de la consulta
            mysqli_stmt_store_result($stmt);

            // Verifica si se encontró algún usuario con el nombre de usuario proporcionado
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Asocia las columnas del resultado a variables
                mysqli_stmt_bind_result($stmt, $storedPassword, $userType, $nombreUsuario, $apellidoUsuario);
                // Obtiene los datos del resultado
                mysqli_stmt_fetch($stmt);

                // Verifica si la contraseña proporcionada coincide con la almacenada en la base de datos
                if (password_verify($password, $storedPassword)) {
                    // Verifica si el usuario es un administrador
                    if ($userType === 'Administrador') {
                        // Establece las variables de sesión para el usuario autenticado
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $username;
                        $_SESSION['userType'] = $userType;
                        $_SESSION['nombre'] = $nombreUsuario;
                        $_SESSION['apellido'] = $apellidoUsuario;

                        // Redirige al usuario al panel de administrador
                        header("Location: ../public/admin_dashboard.php");
                        exit(); // Detiene la ejecución del script después de la redirección
                    } else {
                        // Mensaje de error si el usuario no tiene permisos de administrador
                        $errorMessage = "No tienes permisos de administrador.";
                    }
                } else {
                    // Mensaje de error si la contraseña no coincide
                    $errorMessage = "Los datos introducidos son incorrectos.";
                }
            } else {
                // Mensaje de error si no se encontró al usuario
                $errorMessage = "Los datos introducidos son incorrectos.";
            }

            // Cierra la consulta preparada
            mysqli_stmt_close($stmt);
        } else {
            // Mensaje de error si no se pudo preparar la consulta
            $errorMessage = "Error al preparar la consulta.";
        }

        // Cierra la conexión con la base de datos
        mysqli_close($conn);
    }

    // Si hay un mensaje de error, redirige al formulario con el mensaje como parámetro en la URL
    if (isset($errorMessage)) {
        header("Location: ../public/signin.php?error=" . urlencode($errorMessage) . "&username=" . urlencode($username));
        exit(); // Detiene la ejecución del script después de la redirección
    }
}
?>