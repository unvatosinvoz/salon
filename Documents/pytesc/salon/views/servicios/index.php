<h1 class="nombre-pagina"><?php echo $nombre; ?></h1>
            <p class="descripcion-pagina">Administra los servicios desde esta sección</p>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>

            <ul class="servicios">
<?php foreach($servicios as $servicio) : ?>

                <li class="servicio">
                    <p>Nombre: <span><?php echo $servicio->nombre; ?></span></p>
                    <p>Precio: <span>$<?php echo $servicio->precio; ?></span></p>

                    <div class="acciones">
                        <a href="/servicios/actualizar?id=<?php echo $servicio->id; ?>" class="boton-azul">Actualizar</a>
                        <form action="/servicios/eliminar" method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">
                            <button type="submit" class="eliminar" aria-label="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="16" height="16" viewBox="0 0 24 24">
                                    <path d="M 10 2 L 9 3 L 4 3 L 4 5 L 5 5 L 5 20 C 5 20.522222 5.1913289 21.05461 5.5683594 21.431641 C 5.9453899 21.808671 6.4777778 22 7 22 L 17 22 C 17.522222 22 18.05461 21.808671 18.431641 21.431641 C 18.808671 21.05461 19 20.522222 19 20 L 19 5 L 20 5 L 20 3 L 15 3 L 14 2 L 10 2 z M 7 5 L 17 5 L 17 20 L 7 20 L 7 5 z M 9 7 L 9 18 L 11 18 L 11 7 L 9 7 z M 13 7 L 13 18 L 15 18 L 15 7 L 13 7 z"></path>
                                </svg>
                                <span class="texto-boton">Eliminar</span>
                            </button>
                        </form>
                    </div>
                </li>
<?php endforeach; ?>
            </ul>