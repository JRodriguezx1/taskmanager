<div class="contenedor login">
    <h1> Up Task</h1>  <!-- clase en base/tipografia.scss -->
    <p class="tagline"> Crea y administra tus proyectos</p> <!-- clase en base/tipografia.scss -->

    
    <div class="contenedor-sm">
    <?php
    include_once __DIR__."/../templates/alertas.php";
    ?>
        <p class="descripcion-pagina">Iniciar sesion</p> <!-- clase en base/tipografia.scss -->

        <form class="formulario" method="POST" action="/" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="Tu Email">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Tu Password">
            </div>
            <input class="boton" type="submit" value="Iniciar Seccion">
        </form>

        <div class="acciones">
            <a href="/crearcuenta">¿Aun no tiene una cuenta? Crear una</a>
            <a href="/olvide">Olvidaste tu password</a>
        </div>

    </div> <!-- cierre class conenedor-sm -->

</div>







