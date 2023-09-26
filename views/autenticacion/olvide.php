<div class="contenedor olvide">
    <h1> Up Task</h1>  
    <p class="tagline"> Crea y administra tus proyectos</p> <!-- clase en base/tipografia.scss -->

    
    <div class="contenedor-sm">
     <?php include_once __DIR__."/../templates/alertas.php"; ?>
        <p class="descripcion-pagina">Olvidaste tu contraseña</p> <!-- clase en base/tipografia.scss -->

        <form class="formulario" method="POST" action="/olvide">
            <div class="campo">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="Tu Email">
            </div>
            <input class="boton" type="submit" value="Enviar">
        </form>

        <div class="acciones">
            <a href="/crearcuenta">¿Aun no tiene una cuenta? Crear una</a>
            <a href="/">¿Ya tienes cuenta? Iniciar Sesion</a>
        </div>

    </div> <!-- cierre class conenedor-sm -->

</div>
