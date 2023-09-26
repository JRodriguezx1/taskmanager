<div class="contenedor recuperar">
    <h1> Up Task</h1>  <!-- clase en base/tipografia.scss -->
    <p class="tagline"> Crea y administra tus proyectos</p> <!-- clase en base/tipografia.scss -->

    
    <div class="contenedor-sm">
    <?php
    include_once __DIR__."/../templates/alertas.php";
    if(!$error){
    ?>
    
        <p class="descripcion-pagina">Coloca tu nuevo password</p> <!-- clase en base/tipografia.scss -->

        <form class="formulario" method="POST"> <!-- se quita el accion para no perder la refernecia del token que esta en la url-->
            
            <div class="campo">
                <label for="password">Nuevo Password</label>
                <input id="password" type="password" name="password" placeholder="Nuevo Password">
            </div>
            <input class="boton" type="submit" value="Guardar Nuevo password">
        </form>
    <?php } ?>    

        <div class="acciones">
            <a href="/crearcuenta">¿Aun no tiene una cuenta? Crear una</a>
            <a href="/olvide">Olvidaste tu password</a>
        </div>

    </div> <!-- cierre class conenedor-sm -->

</div>