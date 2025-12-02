<?php
if (!empty($alertas)) {
    foreach ($alertas as $tipo => $mensajes) {
        foreach ($mensajes as $mensaje) {
            echo "<div class='alerta {$tipo}'>{$mensaje}</div>";
        }
    }
}
?>