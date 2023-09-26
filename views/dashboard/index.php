<?php include_once __DIR__."/header_dashboard.php"; ?>  <!-- contiene la barra lateral y superior -->

<div class="contenedor-sm"> <!-- esto hacia abajo esta dentro del div de class contenido el cual este div class contenido esta el header_dashboard.php -->
    <?php
    include_once __DIR__."/../templates/alertas.php";
    ?>

    <?php   if(count($proyectos)==0){ ?>
        <p class="no-proyectos">No hay proyectos aun</p><a href="/crear_proyectos">Crear un proyecto ahora</a>
    <?php }else{ ?>
        <ul class="listado-proyectos">
            <?php foreach($proyectos as $proyecto){ ?>
                <li class="proyecto"> 
                    <a href="/proyecto?id=<?php echo $proyecto->url; ?>"><?php echo $proyecto->nombre; ?></a>
                </li>
            <?php } ?>

        </ul>

    <?php } ?>


</div>

<?php include_once __DIR__."/footer_dashboard.php"; ?>
