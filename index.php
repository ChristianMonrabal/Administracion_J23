<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ./public/signin.php");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="shortcut icon" href="./img/icon.png" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2>Bienvenido a J23</h2>
            </div>
            <div class="card-body">
                <h5 class="card-title">Hola, <?php echo htmlspecialchars($_SESSION['nombre']) . " " . htmlspecialchars($_SESSION['apellido']); ?>!</h5>
                <p class="card-text">Tu rol es: <strong><?php echo htmlspecialchars($_SESSION['userType']); ?></strong></p>
                <form action="./private/logout.php" method="POST" class="mt-3">
                    <button type="submit" class="btn btn-danger">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
