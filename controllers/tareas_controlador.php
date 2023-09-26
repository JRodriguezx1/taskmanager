<?php

namespace Controllers;

use JetBrains\PhpStorm\Internal\ReturnTypeContract;
use Model\proyecto;
use Model\tareas;

class tareas_controlador{

    public static function index(){

        $url = $_GET['id'];
        if(!$url){
            header('Location: /dashboard');
        }
        $proyecto = proyecto::find('url', $url);

        session_start();
        if(!$proyecto || ($proyecto->idusuarios != $_SESSION['id'])){
            header('Location: /404');
        }
        $tareas = tareas::idusuario('idproyectos', $proyecto->id);  //el metodo idusuarios me trae todos los registro especificando el idproyectos
        echo json_encode($tareas);
    }



    public static function crear(){ //esta funcion se visita por medio dle fetch api en js en a funcion agragartarea() de js

        if($_SERVER['REQUEST_METHOD']==='POST'){

            session_start();
            $url = $_POST['url'];  //$_POST viene fromdate que e enviado por medio de fetch en tarea.js
            $proyecto = proyecto::find('url', $url);

            if(!$proyecto || ($proyecto->idusuarios != $_SESSION['id'])){
                $respuesta = ['tipo'=>'error', 'mensaje'=>'hubo un error al agregar la tarea'];
                echo json_encode($respuesta);
                return;
            }else{
                
                $tarea = new tareas($_POST);
                $tarea->idproyectos = $proyecto->id;
                $resultado = $tarea->crear_guardar();
                if($resultado){
                    $respuesta = ['tipo'=>'exito', 'mensaje'=>'tarea agregada correctamente', 'resultado'=>$resultado, 'idproyectos'=>$proyecto->id];
                    echo json_encode($respuesta);
                }
                
            }

        }  
    }



    public static function actualizar(){

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $proyecto = proyecto::find('url', $_POST['url']);
            session_start();

            if(!$proyecto || ($proyecto->idusuarios != $_SESSION['id'])){
                $respuesta = ['tipo'=>'error', 'mensaje'=>'hubo un error al actualizar la tarea'];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new tareas($_POST);
            $resultado = $tarea->actualizar();
            if($resultado){
                $respuesta = ['tipo'=>'exito', 'id'=>$tarea->id, 'idproyectos'=>$proyecto->id];
                echo json_encode($respuesta);
            }


            
        }
        
    }



    public static function eliminar(){

        if($_SERVER['REQUEST_METHOD']==='POST'){

            $proyecto = proyecto::find('url', $_POST['url']);
            session_start();

            if(!$proyecto || ($proyecto->idusuarios != $_SESSION['id'])){
                $respuesta = ['tipo'=>'error', 'mensaje'=>'hubo un error al eliminar la tarea'];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new tareas($_POST);
            $resultado = $tarea->eliminar_registro();
            if($resultado){
                $respuesta = ['tipo'=>'exito', 'resultado'=>$resultado];
                echo json_encode($respuesta);
            }

        }
        
    }

}