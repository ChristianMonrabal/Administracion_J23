<?php
// Mostrar SweetAlert si el usuario fue creado
if (isset($_SESSION['usuario_creado']) && $_SESSION['usuario_creado'] === true) {
    echo "<script src='../js/sweetalert.js'></script>";
    echo "<script>document.addEventListener('DOMContentLoaded', function() { alertaUsuarioCreado(); });</script>";
    unset($_SESSION['usuario_creado']); // Limpiar la variable de sesión
}

// Mostrar SweetAlert si el usuario fue eliminado
if (isset($_SESSION['usuario_eliminado']) && $_SESSION['usuario_eliminado'] === true) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script src='../js/eliminar.js'></script>";
    echo "<script>alertaUsuarioEliminado();</script>";
    unset($_SESSION['usuario_eliminado']); // Limpiar la variable de sesión
}

if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']);
}

if (isset($_SESSION['usuario_eliminado']) && $_SESSION['usuario_eliminado'] === true) {
    echo "<script>document.addEventListener('DOMContentLoaded', function() { alertaUsuarioEliminado(); });</script>";
    unset($_SESSION['usuario_eliminado']);
}
?>