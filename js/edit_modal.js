document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editarAlumnoModal');
    if (modal && modal.getAttribute('data-open') === 'true') {
        $('#editarAlumnoModal').modal('show');
    }
});
