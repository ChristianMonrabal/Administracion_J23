// Agregar evento blur a cada campo del formulario "crearAlumnoForm"
document.getElementById('nombre').addEventListener('blur', function() {
    validateNombre();
});

document.getElementById('apellido').addEventListener('blur', function() {
    validateApellido();
});

document.getElementById('correo').addEventListener('blur', function() {
    validateCorreo();
});

document.getElementById('curso').addEventListener('blur', function() {
    validateCurso();
});

// Agregar evento blur a cada campo del formulario "editarAlumnoForm"
document.getElementById('editar_nombre').addEventListener('blur', function() {
    validateEditarNombre();
});

document.getElementById('editar_apellido').addEventListener('blur', function() {
    validateEditarApellido();
});

document.getElementById('editar_correo').addEventListener('blur', function() {
    validateEditarCorreo();
});

document.getElementById('editar_curso').addEventListener('blur', function() {
    validateEditarCurso();
});

// Funciones de validación para el formulario de creación de alumno

function validateNombre() {
    const nombre = document.getElementById('nombre').value.trim();
    if (nombre === "") {
        showError('nombre', 'El campo nombre es obligatorio.');
    } else {
        clearError('nombre');
    }
}

function validateApellido() {
    const apellido = document.getElementById('apellido').value.trim();
    if (apellido === "") {
        showError('apellido', 'El campo apellido es obligatorio.');
    } else {
        clearError('apellido');
    }
}

function validateCorreo() {
    const correo = document.getElementById('correo').value.trim();
    if (correo === "") {
        showError('correo', 'El campo correo es obligatorio.');
    } else if (!validateEmail(correo)) {
        showError('correo', 'El formato del correo no es válido.');
    } else {
        clearError('correo');
    }
}

function validateCurso() {
    const curso = document.getElementById('curso').value;
    if (curso === "") {
        showError('curso', 'El campo curso es obligatorio.');
    } else {
        clearError('curso');
    }
}

// Funciones de validación para el formulario de edición de alumno

function validateEditarNombre() {
    const nombre = document.getElementById('editar_nombre').value.trim();
    if (nombre === "") {
        showError('editar_nombre', 'El campo nombre no puede estar vacio.');
    } else {
        clearError('editar_nombre');
    }
}

function validateEditarApellido() {
    const apellido = document.getElementById('editar_apellido').value.trim();
    if (apellido === "") {
        showError('editar_apellido', 'El campo apellido no puede estar vacio.');
    } else {
        clearError('editar_apellido');
    }
}

function validateEditarCorreo() {
    const correo = document.getElementById('editar_correo').value.trim();
    if (correo === "") {
        showError('editar_correo', 'El campo correo no puede estar vacio.');
    } else if (!validateEmail(correo)) {
        showError('editar_correo', 'El formato del correo no es válido.');
    } else {
        clearError('editar_correo');
    }
}

function validateEditarCurso() {
    const curso = document.getElementById('editar_curso').value;
    if (curso === "") {
        showError('editar_curso', 'El campo curso no puede estar vacio.');
    } else {
        clearError('editar_curso');
    }
}

// Función para mostrar los errores debajo de cada campo
function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    // Solo agregar el mensaje de error si no existe uno ya
    if (!field.closest('.form-group').querySelector('.text-danger')) {
        const errorElement = document.createElement('small');
        errorElement.classList.add('text-danger');
        errorElement.innerText = message;
        const parent = field.closest('.form-group'); // Encuentra el div .form-group
        parent.appendChild(errorElement); // Añadir el mensaje de error
    }
}

// Función para limpiar los errores previos de un campo
function clearError(fieldId) {
    const field = document.getElementById(fieldId);
    const errorElement = field.closest('.form-group').querySelector('.text-danger');
    if (errorElement) {
        errorElement.remove(); // Elimina el mensaje de error
    }
}



