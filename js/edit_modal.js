document.addEventListener('DOMContentLoaded', function () {
    const crearModal = document.getElementById('crearAlumnoModal');
    if (crearModal && crearModal.getAttribute('data-open') === 'true') {
        $('#crearAlumnoModal').modal('show');
    } else if (crearModal) {
        $('#crearAlumnoModal').modal('hide');
    }

    const editarModal = document.getElementById('editarAlumnoModal');
    if (editarModal && editarModal.getAttribute('data-open') === 'true') {
        $('#editarAlumnoModal').modal('show');
    } else if (editarModal) {
        $('#editarAlumnoModal').modal('hide');
    }
});

$('#crearAlumnoModal .close, #crearAlumnoModal [data-dismiss="modal"]').on('click', function () {
    $('#crearAlumnoModal').modal('hide');
    document.getElementById('crearAlumnoModal').setAttribute('data-open', 'false');
});

$('#editarAlumnoModal .close, #editarAlumnoModal [data-dismiss="modal"]').on('click', function () {
    $('#editarAlumnoModal').modal('hide');
    document.getElementById('editarAlumnoModal').setAttribute('data-open', 'false');
});
