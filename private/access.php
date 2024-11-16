<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $username = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($username) || empty($password)) {
        $errorMessage = "Por favor, complete todos los campos.";
    } else {
        include("../db/conexion.php");

        $query = "SELECT password_usuario, tipo_usuario, nombre_usuario, apellido_usuario FROM usuarios WHERE username_usuario = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                mysqli_stmt_bind_result($stmt, $storedPassword, $userType, $nombreUsuario, $apellidoUsuario);
                mysqli_stmt_fetch($stmt);

                if (password_verify($password, $storedPassword)) {
                    if ($userType === 'Administrador') {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $username;
                        $_SESSION['userType'] = $userType;
                        $_SESSION['nombre'] = $nombreUsuario;
                        $_SESSION['apellido'] = $apellidoUsuario;

                        header("Location: ../index.php");
                        exit();
                    } else {
                        $errorMessage = "No tienes permisos de administrador.";
                    }
                } else {
                    $errorMessage = "Los datos introducidos son incorrectos.";
                }
            } else {
                $errorMessage = "Los datos introducidos son incorrectos.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $errorMessage = "Error al preparar la consulta.";
        }

        mysqli_close($conn);
    }

    if (isset($errorMessage)) {
        header("Location: ../public/signin.php?error=" . urlencode($errorMessage) . "&username=" . urlencode($username));
        exit();
    }
}
?>
