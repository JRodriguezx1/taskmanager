<div class="contenedor crear">
    <h1> Up Task</h1>  <!-- clase en base/tipografia.scss -->
    <p class="tagline"> Crea y administra tus proyectos</p> <!-- clase en base/tipografia.scss -->

    
    <div class="contenedor-sm">
    <?php
    include_once __DIR__."/../templates/alertas.php";
    ?>
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p> <!-- clase en base/tipografia.scss -->

        <form class="formulario" method="POST" action="/crearcuenta">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input id="nombre" type="text" name="nombre" placeholder="Tu Nombre" value="<?php echo $usuario->nombre ?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="Tu Email" value="<?php echo $usuario->email ?>">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Tu Password">
            </div>
            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input id="password2" type="password" name="password2" placeholder="Repite Tu Password">
            </div>
            <input class="boton" type="submit" value="Crear Cuenta">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar Sesion</a>
            <a href="/olvide">Olvidaste tu password</a>
        </div>

    </div> <!-- cierre class conenedor-sm -->

</div>