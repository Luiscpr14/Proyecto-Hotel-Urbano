// Validar que solo se permitan caracteres alfanuméricos y espacios en campos de texto
function validarTexto(input) {
    input.addEventListener('input', function() {
        // Permite letras, números y espacios, elimina caracteres especiales
        this.value = this.value.replace(/[^a-zA-Z0-9áéíóúñ\s]/g, '');
    });
}
let editar = document.querySelector('#editar_form');
editar.addEventListener('submit', validarFormularioEditar);

let crear = document.querySelector('#crear_form');
crear.addEventListener('submit', validarFormularioCrear);

let confpag = document.querySelector('.form-fechas');
confpag.addEventListener('submit', function(event) {
    if (!validarConfPag()) {
        event.preventDefault(); // Detiene el envío si el usuario cancela
    }
});

function validarFormularioCrear() {
    return confirm('¿Deseas agregar estas nuevas habitaciones?');
}

function validarFormularioEditar(){
    return confirm('¿La información editar es correcta?');
}

function validarConfPag(){
    return confirm('Al aceptar y pagar, usted acepta nuestros términos y condiciones');
}

document.addEventListener('DOMContentLoaded', function() {
    // Buscar todos los inputs de texto y aplicar validación
    const inputsTexto = document.querySelectorAll('input[type="text"]');
    inputsTexto.forEach(input => {
        // Solo validar inputs que no sean de búsqueda
        if (input.id !== 'codigo') {
            validarTexto(input);
        }
    });
    // Validar textarea
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        validarTexto(textarea);
    });
});