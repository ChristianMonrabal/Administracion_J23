function cerrarSesion() {
    Swal.fire({
        title: '¿Estás seguro de cerrar sesión?',
        text: "No podrás recuperar tu sesión después de esto.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir a logout.php para cerrar sesión
            window.location.href = '../private/logout.php';
        }
    });
}