<h1 class="nombre-pagina"><?php echo $nombre; ?></h1>
                <p class="descripcion-pagina">Administra los servicios desde esta sección</p>

                <?php include_once __DIR__ . '/../templates/barra.php'; ?>
            <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
<form action="/servicios/actualizar?id=<?php echo $servicio->id; ?>" class="formulario" method="POST">
                <?php include_once __DIR__ . '/formulario.php'; ?>

                <input type="submit" class="boton" value="Actualizar Servicio">
            </form>