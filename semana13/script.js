
document.addEventListener("DOMContentLoaded", function () {

    // se obtiene el formulario y sus campos
    const formulario = document.getElementById("formularioInscripcion");
    const nombre = document.getElementById("nombre");
    const correo = document.getElementById("correo");
    const curso = document.getElementById("curso");

    // se obtienen los espacios para mostrar errores
    const errorNombre = document.getElementById("errorNombre");
    const errorCorreo = document.getElementById("errorCorreo");
    const errorCurso = document.getElementById("errorCurso");

    // se comprueba el formulario antes de enviarlo
    formulario.addEventListener("submit", function (evento) {

        // se limpian los mensajes anteriores
        errorNombre.textContent = "";
        errorCorreo.textContent = "";
        errorCurso.textContent = "";

        // se inicializa el estado de validacion
        let formularioValido = true;

        // se valida el nombre
        if (nombre.value.trim() === "") {
            errorNombre.textContent = "Ingrese su nombre";
            formularioValido = false;
        }

        // se valida el correo
        if (!correo.validity.valid || correo.value.trim() === "") {
            errorCorreo.textContent = "Ingrese un correo valido";
            formularioValido = false;
        }

        // se valida la seleccion del curso
        if (curso.value === "") {
            errorCurso.textContent = "Seleccione un curso";
            formularioValido = false;
        }

        // se detiene el envio si hay errores
        if (!formularioValido) {
            evento.preventDefault();
        }
    });

    // se limpia el mensaje del nombre cuando se escribe
    nombre.addEventListener("input", function () {
        if (nombre.value.trim() !== "") {
            errorNombre.textContent = "";
        }
    });

    // se limpia el mensaje del correo cuando se escribe
    correo.addEventListener("input", function () {
        if (correo.validity.valid && correo.value.trim() !== "") {
            errorCorreo.textContent = "";
        }
    });

    // se limpia el mensaje del curso cuando se selecciona una opcion
    curso.addEventListener("change", function () {
        if (curso.value !== "") {
            errorCurso.textContent = "";
        }
    });

});
