<?php

namespace Controllers;

use Model\proyecto;
use Model\usuarios;
use MVC\Router;


class dashboard_controlador{

    public static function index(Router $router){  //cuando se presiona el boton proyectos del dashboard, 
        session_start();
        isAuth();
        $alertas = [];
        
        $id = $_SESSION['id'];
        $proyectos = proyecto::idusuario('idusuarios', $id);  //en la tabla proyecto se trae los registros del id indicado, devuelve arreglo con los objetos de cada registro
        

        $router->render('dashboard/index', ['titulo'=>'proyectos', 'proyectos'=>$proyectos, 'alertas'=>$alertas]);

    }


    public static function crear_proyectos(Router $router){
        session_start();
        isAuth();
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            $proyecto = new proyecto($_POST);
            $alertas = $proyecto->validar_proyecto();
            if(empty($alertas)){
                //generar url unica
                $proyecto->url = md5(uniqid());
                //almacenar el creardor de proyecto
                $proyecto->idusuarios = $_SESSION['id'];
                //guardar
                $proyecto->crear_guardar();
                //redireccion
                header('Location: /proyecto?id='.$proyecto->url);
            }

        }
        $router->render('dashboard/crear_proyectos', ['titulo'=>'crear_proyectos', 'alertas'=>$alertas]);
    }



    public static function proyecto(Router $router){ //metodo se llama cuando se crea un proyecto
        session_start();
        isAuth();
        //revisar que la persona quien visita el proyecto es quien lo creo
        $url = $_GET['id'];

        if(!$url){
            
            header('Location: /dashboard'); 
        }

        $proyecto = proyecto::find('url', $url);
        if($proyecto->idusuarios !== $_SESSION['id']){
            header('Location: /dashboard');
        }


        $router->render('dashboard/proyecto', ['titulo'=>$proyecto->nombre]);
    }



    public static function perfil(Router $router){
        session_start();
        isAuth();
        $alertas = [];
        $usuario = usuarios::find('id', $_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $usuario->compara_objetobd_post($_POST);
            $alertas = $usuario->validar_perfil();
            if(empty($alertas)){ //si esta vacio las alertas, es pq no hay errores, y guarda los datos
                
                $existeusuario = usuarios::find('email', $_POST['email']); //valida que el correo actualizado no exista
                
                if($existeusuario && ($_SESSION['email']!=$_POST['email'])){  //si existe email mostrar error y si cambia email en el formulario, es pq quizo cambiar el email, si se mantiene el mismo email es pq qizas solo cambio el nombre
                    $alertas['error'][] = 'ya existe correo electronico';
                }else{ //si no existe email, actualizar el nuevo correo electronico
                    $resultado = $usuario->actualizar();
                    if($resultado){
                    $_SESSION['nombre'] = $usuario->nombre;  //se actualiza el nombre modificado
                    header('Location: /dashboard');
                                  }
                }   
            }
        }
        
        $router->render('dashboard/perfil', ['titulo'=>'perfil', 'alertas'=>$alertas, 'usuario'=>$usuario]);
    }



    public static function cambiar_password(Router $router){

        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $usuario = usuarios::find('id', $_SESSION['id']);  //se trae los datos del usuario actual logeado de la bd
           
            $validar_pass_antiguo = password_verify($_POST['password'], $usuario->password); //validar la contraseña  ingresada en el formulario si es la misma en la bd

            if($validar_pass_antiguo){  

                $validar_pass_new = password_verify($_POST['password2'], $usuario->password); //valida si la contraseña2 del formulario es la misma en la bd
                if($validar_pass_new){
                    usuarios::setAlerta('error', 'la contraseña es igual, ingresar otra');
                }

                $usuario->compara_objetobd_post($_POST);
                $usuario->password = $usuario->password2;
                $alertas = $usuario->validar_password(); //valida que el campo de password cumpla con los requisitos
                
                if(empty($alertas)){
                    //hashear nueva contraseña
                    $usuario->hashearpass();
                    //guardar nueva contraseña
                    $resultado = $usuario->actualizar();
                    if($resultado)
                    $alertas['exito'][] = 'contraseña actualizada';

                }



            }
            else{
                $alertas['error'][] = 'contraseña actual no es correcto';
            }
            
        }

        $router->render('dashboard/cambiar_password', ['titulo'=>'cambiar password', 'alertas'=>$alertas]);
    }
}