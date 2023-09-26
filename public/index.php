<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\dashboard_controlador;
use Controllers\login_controlador;
use Controllers\tareas_controlador;
use MVC\Router;
$router = new Router();


//iniciar session
$router->get('/', [login_controlador::class, 'login']);
$router->post('/', [login_controlador::class, 'login']);
//logout
$router->get('/logout', [login_controlador::class, 'logout']);
//crear cuenta
$router->get('/crearcuenta', [login_controlador::class, 'crear_cuenta']);
$router->post('/crearcuenta', [login_controlador::class, 'crear_cuenta']);
//formulario de olvide mis password
$router->get('/olvide', [login_controlador::class, 'olvide']);
$router->post('/olvide', [login_controlador::class, 'olvide']);
//colocar el nuevo password
$router->get('/recuperar', [login_controlador::class, 'recuperar']);
$router->post('/recuperar', [login_controlador::class, 'recuperar']);
//conformacion de cuenta
$router->get('/confirmar_cuenta', [login_controlador::class, 'confirmar_cuenta']);
$router->get('/mensaje', [login_controlador::class, 'mensaje']);
//zona de proyectos dentro del dashboard
$router->get('/dashboard', [dashboard_controlador::class, 'index']); //muestra todos los proyectos
$router->get('/crear_proyectos', [dashboard_controlador::class, 'crear_proyectos']);
$router->post('/crear_proyectos', [dashboard_controlador::class, 'crear_proyectos']);  //formulario de creara proyectos
$router->get('/proyecto', [dashboard_controlador::class, 'proyecto']);  //se llama cuando se crea un proyecto o cuando se da click en un proyecto
$router->get('/perfil', [dashboard_controlador::class, 'perfil']);
$router->post('/perfil', [dashboard_controlador::class, 'perfil']);
$router->get('/cambiar_password', [dashboard_controlador::class, 'cambiar_password']);
$router->post('/cambiar_password', [dashboard_controlador::class, 'cambiar_password']);
//API para las tareas
$router->get('/api/tareas', [tareas_controlador::class, 'index']); //consultar y obtener todas las tareas de un proyecto
$router->post('/api/tarea', [tareas_controlador::class, 'crear']); //se visita y se ejecuta metodo crear, al momento de crear tarea con la funcion creartarea() con fetch de js (enpoint)
$router->post('/api/tarea/actualizar', [tareas_controlador::class, 'actualizar']); //enpoint que recibe peticion post desde js por fetch al momento de cambiar el estado de la tarea
$router->post('/api/tarea/eliminar', [tareas_controlador::class, 'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();