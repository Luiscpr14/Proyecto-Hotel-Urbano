// Código que configura las validaciones: se ejecuta al cargar el script.
// - Checa inputs para sanitizar mientras se escribe.
// - Checa los submit y la función validarFormulario se ejecuta al enviar el formulario
(function () {
    'use strict';

    // Helpers
    function safeQuery(selector) {
        return document.querySelector(selector) || null;
    }

    function addIfExists(el, event, handler) {
        if (el) el.addEventListener(event, handler);
    }

    // Permite letras, números, espacios y acentos (para campos "simples")
    function validarTextoSimple(input) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]/g, '');
        });
    }

    // Para descripciones: permite puntuación básica además de letras y números
    function validarDescripcion(textarea) {
        textarea.addEventListener('input', function () {
            // permite . , ; : - ( ) ' " ? ! y saltos de línea
            this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\,\;\:\-\(\)\'\"\?\!\n]/g, '');
        });
    }

    // Código: solo alfanumérico, máximo 5 caracteres, convertir a mayúsculas
    function validarCodigo(input) {
        input.addEventListener('input', function () {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 5);
        });
    }

    // Validación general del formulario (crear / editar)
    function validarFormulario(event) {
        // event es obligatorio para poder preventDefault si hay problemas o el usuario cancela
        var form = event.target;

        // Obtener valores (los ids están presentes en ambos formularios)
        var codigoEl = document.getElementById('codigo');
        var precioEl = document.getElementById('precio');
        var capacidadEl = document.getElementById('capacidad');
        var disponiblesEl = document.getElementById('disponibles');
        var descripcionEl = document.getElementById('descripcion');
        var imagenEl = document.getElementById('imagen');

        // Validaciones
        if (codigoEl) {
            var codigo = codigoEl.value.trim();
            if (!/^[A-Z0-9]{1,5}$/.test(codigo)) {
                alert('Código inválido. Debe ser alfanumérico (máx. 5 caracteres).');
                codigoEl.focus();
                event.preventDefault();
                return;
            }
        }

        if (precioEl) {
            var precio = parseFloat(precioEl.value);
            if (isNaN(precio) || precio < 0) {
                alert('Ingrese un precio válido (numero >= 0).');
                precioEl.focus();
                event.preventDefault();
                return;
            }
        }

        if (capacidadEl) {
            var capacidad = parseInt(capacidadEl.value, 10);
            if (isNaN(capacidad) || capacidad < 1) {
                alert('La capacidad debe ser un número entero mayor o igual a 1.');
                capacidadEl.focus();
                event.preventDefault();
                return;
            }
        }

        if (disponiblesEl) {
            var disponibles = parseInt(disponiblesEl.value, 10);
            if (isNaN(disponibles) || disponibles < 0) {
                alert('Disponibles debe ser un número entero mayor o igual a 0.');
                disponiblesEl.focus();
                event.preventDefault();
                return;
            }
        }

        if (descripcionEl) {
            var desc = descripcionEl.value.trim();
            if (desc.length < 10) {
                alert('La descripción debe tener al menos 10 caracteres.');
                descripcionEl.focus();
                event.preventDefault();
                return;
            }
            if (desc.length > 2000) {
                alert('La descripción es demasiado larga.');
                descripcionEl.focus();
                event.preventDefault();
                return;
            }
        }

        if (imagenEl && imagenEl.files && imagenEl.files.length > 0) {
            var file = imagenEl.files[0];
            var maxBytes = 2 * 1024 * 1024; // 2 MB
            if (!file.type.startsWith('image/')) {
                alert('El archivo debe ser una imagen.');
                imagenEl.focus();
                event.preventDefault();
                return;
            }
            if (file.size > maxBytes) {
                alert('La imagen supera el tamaño permitido (2 MB).');
                imagenEl.focus();
                event.preventDefault();
                return;
            }
        }

        // Confirmación final (si el usuario cancela, prevenir envío)
        var actionLabel = form.querySelector('input[type="submit"]') ? form.querySelector('input[type="submit"]').value : 'Enviar';
        if (!confirm('¿Deseas continuar y ' + actionLabel + '?')) {
            event.preventDefault();
            return;
        }

        // Si todo bien, el formulario se envía
    }

    // Aplicar validaciones una vez que DOM está listo
    document.addEventListener('DOMContentLoaded', function () {
        // Inputs de texto y textareas
        var inputsTexto = document.querySelectorAll('input[type="text"]');
        inputsTexto.forEach(function (input) {
            if (input.id === 'codigo') {
                validarCodigo(input);
            } else {
                validarTextoSimple(input);
            }
        });

        var textareas = document.querySelectorAll('textarea');
        textareas.forEach(function (ta) {
            validarDescripcion(ta);
        });

        // Ligar envío de formularios solo si existen
        var crearForm = safeQuery('#crear_form');
        var editarForm = safeQuery('#editar_form');
        addIfExists(crearForm, 'submit', validarFormulario);
        addIfExists(editarForm, 'submit', validarFormulario);

        // Si tienes algún formulario con clase .form-fechas, lo manejamos también
        var confpag = safeQuery('.form-fechas');
        if (confpag) {
            confpag.addEventListener('submit', function (e) {
                if (!confirm('Al aceptar y pagar, usted acepta nuestros términos y condiciones')) {
                    e.preventDefault();
                }
            });
        }
    });
})();