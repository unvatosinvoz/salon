<h1 class="nombre-pagina"><?php echo $nombre ?></h1>
            <p class="descripcion-pagina">Desde aquí podrás administrar todo el sitio</p>

            <?php include_once __DIR__ . '/../templates/barra.php'; ?>

            <h2>Buscar Citas</h2>
            <div class="busqueda">
                <form action="" class="formulario">
                    <div class="campo">
                        <label for="fecha">Selecciona una fecha</label>
                        <input 
                            type="date" 
                            id="fecha" 
                            name="fecha"
                            value="<?php echo $fecha; ?>"
                        >
                    </div>
                </form>
            </div>

            <div class="citas-admin">
            <?php if(count($citas) === 0) { ?>
                <h2>No hay citas aún</h2>
                <p>Comienza creando una</p>
            <?php } else { ?>
    <ul class="citas">
    <?php
        $idCita = 0;
        foreach($citas as $key => $cita) { 
            if ($idCita !== $cita->id) {  ?>
                <li>
                        <p>ID: <span><?php echo $cita->id; ?></span></p>
                        <p>Hora: <span><?php echo $cita->hora; ?></span></p>
                        <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
                        <?php
                            $email = filter_var($cita->email ?? '', FILTER_SANITIZE_EMAIL);
                            $escaped = htmlspecialchars($email, ENT_QUOTES);
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                echo "<p>Email: <a href=\"mailto:$escaped\">$escaped</a></p>";
                            } else {
                                echo "<p>Email: <span>$escaped</span></p>";
                            }?>

                        <p>Teléfono: <a href="tel:<?php echo preg_replace('/\D+/', '', $cita->telefono); ?>"><?php echo htmlspecialchars($cita->telefono, ENT_QUOTES); ?></a></p>
                        <h3>Servicios</h3>
    <?php    
        $idCita = $cita->id;
        } ?>
                    <p class="servicio"><?php echo $cita->servicio . " \t $" . $cita->precio; ?></p>                
    <?php 
        $actual = $cita->id;
        $proximo = $citas[$key + 1]->id ?? 0;
        if (esUltimo($actual, $proximo)) { ?>
                    <p class="total">Total a pagar: <span>$<?php echo calcularTotal($citas, $actual); ?></span></p>
                    <form action="/api/eliminar" method="POST" class="formulario-eliminar">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <button type="submit" class="boton-eliminar" aria-label="Eliminar Cita">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true" style="vertical-align:middle; margin-right:6px;">
                                <path d="M5.5 5.5a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5z"/>
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4H2.5a1 1 0 1 1 0-2H5a1 1 0 0 1 .94-.673h3.12A1 1 0 0 1 10 2h2.5a1 1 0 0 1 1 1z"/>
                            </svg>
                            Eliminar Cita
                        </button>
                    </form>
    <?php } 
        }
    } //Fi de Foreach?>                </li>
                </ul>
            </div> <!-- div citas-admin -->

            <?php
                $script = "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script src='/build/js/buscador.js'></script>
                ";
            ?>