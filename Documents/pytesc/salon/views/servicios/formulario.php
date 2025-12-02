<div class="campo">
                    <label for="nombre">Nombre Servicio</label>
                    <input 
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del Servicio"
                        value="<?php echo s($servicio->nombre); ?>"
                    >
                </div>
                <div class="campo">
                    <label for="precio">Precio Servicio</label>
                    <input 
                        type="number"
                        id="precio"
                        name="precio"
                        placeholder="Precio del Servicio"
                        value="<?php echo s($servicio->precio); ?>"
                    >
                </div>