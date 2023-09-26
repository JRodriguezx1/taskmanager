<?php include_once __DIR__."/header_dashboard.php"; ?>

<div class="contenedor-sm">
    <?php
    include_once __DIR__."/../templates/alertas.php";
    ?>

    <a href="/perfil" class="enlace">Volver al perfil</a>

    <form class="formulario" method="POST"  action="/cambiar_password">
        <div class="campo">
            <label for="contraseña">Password Actual</label>
            <input id="contraseña" name="password" type="password" placeholder="Tu contraseña actual">
        </div>
        <div class="campo">
            <label for="contraseña">Nuevo Password</label>
            <input id="contraseña" name="password2" type="password" placeholder="Tu nueva contraseña">
        </div>
        <input  type="submit" value="Guardar cambios">
    </form>

</div>

<?php include_once __DIR__."/footer_dashboard.php"; ?>