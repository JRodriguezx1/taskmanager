<aside class="sidebar">
    <div class="uptask">
        <h2>UpTask</h2>
        <div class="menux">
            <img id="mobile-menux" src="build/img/cerrar.svg" alt="cerrar menu">
        </div>
    </div>
    
    <nav class="sidebar-nav"> <!-- el tamaño de las letras de los links <a> estan definidos en 1.6rem en gloables.scss -->
        <a class="<?php echo ($titulo === 'proyectos')?'activo':''; ?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo ($titulo === 'crear_proyectos')?'activo':''; ?>" href="/crear_proyectos">Crear Proyectos</a>
        <a class="<?php echo ($titulo === 'perfil')?'activo':''; ?>" href="/perfil">Perfil</a>
    </nav>
    <div class="cerrar-sesion-mobile">
        <p>Bienvenido: <span> <?php echo $_SESSION['nombre']; ?></span></p>
        <a class="cerrar-sesion" href="/logout">Cerrar Sesion</a>
    </div>
</aside>