<h1 class="nombre-pagina"><?php echo $nombre; ?></h1>
<p class="descripcion-pagina">Elige tus servicios y coloca tus datos</p>



<div class="barra">
    <p>Hola: <?php echo $cliente ?? ''; ?></p>
    <a href="/logout" class="boton">Cerrar Sesión</a>
</div>

<div id="app">
    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Datos y Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div id="paso-1" class="seccion mostrar">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>
    
    <div id="paso-2" class="seccion">
        <h2>Datos y cita</h2>
        <p class="text-center">Elige tus datos y cita</p>

        <form class="formulario"> <!-- IGNORE: form no se envía se trabajara con JS-->
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    id="nombre"
                    placeholder="Tu Nombre"
                    value="<?php echo $cliente ?? ''; ?>"
                    disabled
                >
            </div>
            <div class="campo">
                <label for="fecha">Fecha</label>
                <input 
                    type="date"
                    id="fecha"
                    min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                >
            </div>
            <div class="campo">
                <label for="hora">Hora</label>
                <input 
                    type="time"
                    id="hora"
                >
            </div>

            <input type="hidden" name="id" id="id" value="<?php echo $id ?? ''; ?>">

        </form>
    </div>
    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">EVerifica que la información sea correcta</p>
    </div>

    <div class="paginacion">
        <button
            id="anterior"
            class="boton"
            type="button"
        >&laquo; Anterior</button>

        <button
            id="siguiente"
            class="boton"
            type="button"
        >Siguiente &raquo;</button>
    </div>
</div>  

<?php 
    //aqui crearemos la variable $script para cargar los scripts necesarios en el layout
    $script = "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
    ";
?>