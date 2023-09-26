<?php include_once __DIR__."/header_dashboard.php"; ?>

<div class="contenedor-sm">
    <?php
    include_once __DIR__."/../templates/alertas.php";
    ?>

    <a href="/cambiar_password" class="enlace">Cambiar Contraseña</a>

    <form class="formulario" method="POST"  action="/perfil">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input id="nombre" name="nombre" type="text" placeholder="Tu nombre" value="<?php echo $usuario->nombre; ?>">
        </div>
        <div class="campo">
            <label for="email">Email</label>
            <input id="email" name="email" type="text" placeholder="Tu email" value="<?php echo $usuario->email; ?>">
        </div>
        <input  type="submit" value="Guardar cambios">
    </form>

</div>

<?php include_once __DIR__."/footer_dashboard.php"; ?>
