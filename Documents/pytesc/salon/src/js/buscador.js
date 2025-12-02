document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    buscarFecha();
}

function buscarFecha() {
    const fechaInput = document.querySelector('#fecha');

    fechaInput.addEventListener('input', function(e) {
        const fechaSeleccionada = e.target.value;

        // Redirigir a la URL con el parámetro de fecha
        window.location = `?fecha=${fechaSeleccionada}`;
        
    });
}