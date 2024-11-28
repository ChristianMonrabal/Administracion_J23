document.addEventListener('DOMContentLoaded', function() {
    console.log("Archivo eliminar.js cargado");

    window.alertaUsuarioEliminado = function() {
        console.log("Ejecutando alertaUsuarioEliminado");
        Swal.fire({
            title: 'Usuario eliminado',
            text: 'El usuario ha sido eliminado exitosamente',
            icon: 'warning',
            confirmButtonText: 'Aceptar'
        });
    };

    window.confirmarEliminacion = function(event, form) {
        event.preventDefault(); // Evita el envío del formulario
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Envía el formulario si el usuario confirma
            }
        });
    };
});