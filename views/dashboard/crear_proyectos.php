<?php include_once __DIR__."/header_dashboard.php"; ?>

<div class="contenedor-sm">
    <?php
    include_once __DIR__."/../templates/alertas.php";
    ?>

    <form class="formulario" method="POST" action="/crear_proyectos">

        <div class="campo">
            <label for="nombre">Nombre proyecto</label>
            <input id="nombre" type="text" name="nombre" placeholder="Nombre del proyecto">
        </div>
        <input type="submit" value="Crear Proyecto">

    </form>

</div>

<?php include_once __DIR__."/footer_dashboard.php"; ?>