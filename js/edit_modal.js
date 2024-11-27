document.addEventListener('DOMContentLoaded', function () {
    // Controlar el modal para creación de alumno
    const crearModal = document.getElementById('crearAlumnoModal');
    if (crearModal && crearModal.getAttribute('data-open') === 'true') {
        $('#crearAlumnoModal').modal('show');
    } else if (crearModal) {
        // Asegurarse de que no se muestre si no corresponde
        $('#crearAlumnoModal').modal('hide');
    }

    // Controlar el modal para edición de alumno
    const editarModal = document.getElementById('editarAlumnoModal');
    if (editarModal && editarModal.getAttribute('data-open') === 'true') {
        $('#editarAlumnoModal').modal('show');
    } else if (editarModal) {
        // Asegurarse de que no se muestre si no corresponde
        $('#editarAlumnoModal').modal('hide');
    }
});

// Opcional: Limpia el estado del modal al cerrarlo manualmente
$('#crearAlumnoModal .close, #crearAlumnoModal [data-dismiss="modal"]').on('click', function () {
    $('#crearAlumnoModal').modal('hide');
    document.getElementById('crearAlumnoModal').setAttribute('data-open', 'false');
});

$('#editarAlumnoModal .close, #editarAlumnoModal [data-dismiss="modal"]').on('click', function () {
    $('#editarAlumnoModal').modal('hide');
    document.getElementById('editarAlumnoModal').setAttribute('data-open', 'false');
});
