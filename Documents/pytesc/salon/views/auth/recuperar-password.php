<h1 class="nombre-pagina"><?php echo $titulo ?></h1>
<p class="descripcion">Reestablece tu password escribiendo tu email a continuación</p>
<?php 
 include_once __DIR__ . '/../templates/alertas.php';
?>

<?php if($error) return; ?> <!-- Si hay un error no mostrar el formulario -->

<!-- observa qeu Formulario no tiene action, esto es para que no se pierda el token que recive por la URL -->
<form class="formulario" method="POST">
    <div class="campo">
        <label for="passwpassword">Contraseña</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Escribe tu nuevo password"
        >
    </div>

    <input type="submit" class="boton" value="Guardar nuevo Password">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>