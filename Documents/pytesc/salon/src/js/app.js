let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion(); //muestra y oculta las secciones
    tabs(); //cambia la seeccion mostrada cuando se presionen los tabs
    botonesPaginador(); //Agrega oquita botones paginador

     // para los botones santerior y siguiente
    paginaSiguiente();
    paginaAnterior();

    //Consultarla API
    consultarAPI();

    nombreCliente(); //Añade el nombre de cliente al objeto cita
    idCliente(); //Añade el id del cliente al objeto cita
    seleccionarFecha(); //Añade la fecah al objeto cita
    seleccionarHora(); //Añade la hora al objeto cita

    mostrarResumen(); //Muestra el resumen de la cita (servicios, fecha, hora y nombre)
}

function mostrarSeccion(){
    //ocultar las sección que tenga la clase mostrar par aocultarla
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar')
    }

    //Seleccionar la seeción con su paso para mostrarla
    const seeccion = document.querySelector(`#paso-${paso}`);
    seeccion.classList.add('mostrar');

    //quita la clase actual
    const tabAnterior = document.querySelector('.actual')
    if(tabAnterior){
        tabAnterior.classList.remove('actual')
    }

    //resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`)
    tab.classList.add('actual')
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button')

    botones.forEach( boton => {
        boton.addEventListener('click', function(e) {
            paso = parseInt(e.target.dataset.paso );
            //console.log(e.target.dataset.paso );
            mostrarSeccion();

            botonesPaginador();

            if(paso === 3){
                mostrarResumen();
            }
        });
    });
}

function botonesPaginador(){
    const paginaAnterior = document.querySelector('#anterior')
    const paginaSiguiente = document.querySelector('#siguiente')

    if(paso === 1) {
        paginaSiguiente.classList.remove('ocultar')
        paginaAnterior.classList.add('ocultar')
    }else if(paso === 2){
        paginaAnterior.classList.remove('ocultar')
        paginaSiguiente.classList.remove('ocultar')
    }else if (paso === 3){
        paginaAnterior.classList.remove('ocultar')
        paginaSiguiente.classList.add('ocultar')
    }

    mostrarSeccion()
}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior')
    paginaAnterior.addEventListener('click', function(){
        if(paso <= pasoInicial) return
        
        paso--
        botonesPaginador()
    })
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente')
    paginaSiguiente.addEventListener('click', function(){
        if(paso >= pasoFinal) return;

        //console.log(paginaSiguiente);
        
        paso++;
        botonesPaginador();
    })
}

//funci+on asincrona para descargar datos
async function consultarAPI() {
    try {
        const url = "/api/servicios"
        const resultado = await fetch(url)

        const servicios = await resultado.json()
        //console.log(servicios)

        mostrasServicios(servicios)

    } catch (error) {
        console.log(error)
    }
}

function mostrasServicios(servicios) {
    servicios.forEach( servicio => {
        const { id, nombre, precio } = servicio

        const nombreServicio = document.createElement('P')
        nombreServicio.classList.add('nombre-servicio')
        nombreServicio.textContent = nombre

        const precioServicio = document.createElement('P')
        precioServicio.classList.add('precio-servicio')
        //precioServicio.textContent = `$${precio}`
        const precioFmt = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(Number(precio));
        precioServicio.textContent = precioFmt;

        const servicioDiv = document.createElement('DIV')
        servicioDiv.classList.add('servicio')
        servicioDiv.dataset.idServicio = id
        servicioDiv.onclick = function() {
            selecionarServicio(servicio)
        }

        servicioDiv.appendChild(nombreServicio)
        servicioDiv.appendChild(precioServicio)
        
        //identificar al elemento al que se le da click
        document.querySelector('#servicios').appendChild(servicioDiv)

        //console.log(precioServicio)
    })
}

function selecionarServicio(servicio){
    //console.log("desde seleccioanr servicio")
    const { id } = servicio
    const  { servicios } = cita

    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`)

    //comprobar siun servicio ya fue seleccionado
    if( servicios.some( agregado  => agregado.id === id)){
        //eliminarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id )
        divServicio.classList.remove('seleccionado');
    }else {
        //agregarlo
        cita.servicios = [...servicios, servicio]
        divServicio.classList.add('seleccionado');
    }

    //console.log(cita)
}

function nombreCliente () {
    // Si el input #nombre existe, tómalo al cargar y también escucha cambios
    const input = document.querySelector('#nombre');
    if (!input) return;
        
    cita.nombre = input.value.trim();

    //console.log(cita)
}

function idCliente () {
    // Si el input #id existe, tómalo al cargar y también escucha cambios
    const input = document.querySelector('#id');
    if (!input) return;
        
    cita.id = input.value.trim();

    //console.log(cita)
}
function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha')
    
    inputFecha.addEventListener('input', function(e){
        const dia = new Date(e.target.value).getUTCDay()
        if( [6, 0].includes(dia)) {
            e.target.value = ''
            //Swal.fire({ icon: 'error', title: 'Error', text: 'No abrimos esa fecha' });
            mostrarAlerta("Fines de semana no abrimos", 'error', '.formulario', false)
        }else{
            //console.log("Correcto")
            cita.fecha = e.target.value
            Swal.fire('Correcto', 'Fecha disponible', 'success');
        }
    })
}

function seleccionarHora(){
    const inputHora = document.querySelector('#hora')
    inputHora.addEventListener('input', function(e) {
        const horacita = e.target.value
        const hora = horacita.split(":")[0]

        if(hora < 10 || hora > 18){
            e.target.value = ''
            mostrarAlerta("Hora no válida.", 'error', '.formulario', false)
        }else {
            cita.hora = e.target.value
        }
    })

}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){
    //previene que se duplique la alerta
    const alertaPrevia = document.querySelector('.alerta')
    if(alertaPrevia) return

    //Scriptin para mostrar la alerta
    const alerta = document.createElement('DIV')
    alerta.textContent = mensaje
    alerta.classList.add('alerta')
    alerta.classList.add(tipo)

    const referencia = document.querySelector(elemento)
    referencia.appendChild(alerta)

    //elimiar la alaerta despues de 3 segundos.
    if(desaparece) {
        setTimeout(() => {
            alerta.remove()
        }, 3000)
        return
    }
}

function mostrarResumen(){
    //Destructuring
    const { nombre, fecha, hora, servicios } = cita

    //Seleccionar el resumen
    const resumenDiv = document.querySelector('.contenido-resumen')

    //Limpiar el contenido previo
    resumenDiv.innerHTML = ''

    //limpiar el contenido de resumen
    while(resumenDiv.firstChild){
        resumenDiv.removeChild(resumenDiv.firstChild)
    }   

    //Validación de objeto

    if(Object.values(cita).includes('') || servicios.length === 0){
        mostrarAlerta('Faltan datos de servicios, fecha u hora', 'error', '.contenido-resumen', false)
        return
    }

    //Mostrar el headding servicios
    const headingServicios = document.createElement('H3')
    headingServicios.textContent = 'Resumen de Servicios'
    resumenDiv.appendChild(headingServicios)

    //Mostrar los servicios
    servicios.forEach( servicio => {
        const { nombre, precio } = servicio

        const servicioCita = document.createElement('DIV')
        servicioCita.classList.add('contenedor-servicio')

        const nombreServicio = document.createElement('P')
        nombreServicio.textContent = nombre

        const precioServicio = document.createElement('P')
        const precioFmt = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(Number(precio));
        precioServicio.textContent = precioFmt;

        servicioCita.appendChild(nombreServicio)
        servicioCita.appendChild(precioServicio)

        resumenDiv.appendChild(servicioCita)
    })
    //Agregar al resumen cita el nombre, fecha y hora
    const headingCita = document.createElement('H3')
    headingCita.textContent = 'Resumen de Cita'
    resumenDiv.appendChild(headingCita)

    //Mostrar el nombre
    
    const nombreCliente = document.createElement('P')
    nombreCliente.innerHTML = `<span>Nombre:</span> ${(nombre || '').toLocaleUpperCase('es-MX')}`

    //Mostrar la fecha
    const fechaObj = new Date(fecha)
    const mes = fechaObj.getMonth()
    const dia = fechaObj.getDate() + 1
    const year = fechaObj.getFullYear()

    const fechaFormateada = new Intl.DateTimeFormat('es-MX', { dateStyle: 'long' }).format(fechaObj);

    const fechaCita = document.createElement('P')
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`

    //Mostrar la hora
    const horaCita = document.createElement('P')
    horaCita.innerHTML = `<span>Hora:</span> ${hora} hrs`

    resumenDiv.appendChild(nombreCliente)
    resumenDiv.appendChild(fechaCita)
    resumenDiv.appendChild(horaCita)

    //boton para crear la cita
    const botonReservar = document.createElement('BUTTON')
    botonReservar.classList.add('boton')
    botonReservar.textContent = 'Reservar Cita'
    botonReservar.onclick = reservarCita

    resumenDiv.appendChild(botonReservar)

}

async function reservarCita(){
    //enviar la informacion a la API
    //console.log('desde reservar cita')

    const { id, fecha, hora, servicios } = cita

    const idServicios = servicios.map( servicio => servicio.id )
    const datos = new FormData()
    datos.append('usuarioId', id)
    datos.append('fecha', fecha)
    datos.append('hora', hora)
    datos.append('servicios', idServicios.toString())

    //console.log([...datos])

    //Enviar la peticion a la API
    try {
        const url = '/api/citas'

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        })
        const resultado = await respuesta.json()
        console.log(resultado)

        if(resultado.resultado) {
            Swal.fire({
                title: 'Cita creada',
                text: 'Tu cita fue creada correctamente',
                icon: 'success',
                button: 'OK'
            }).then( () => {
                setTimeout(() => {
                    window.location.reload()
                }, 3000);
            })   
        }   
    } catch (error) {
        //console.log(error)
        Swal.fire({
            title: 'Error',
            text: 'Hubo un error al crear la cita',
            icon: 'error',
            button: 'OK'
        })
    }
}   