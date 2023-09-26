<?php

namespace Model;

use Model\ActiveRecord;

 class proyecto extends ActiveRecord{
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'nombre', 'url', 'idusuarios'];

    public $x;

    public function __construct($args = [])
    {
        $this->id = $args['id']??null;
        $this->nombre = $args['nombre']??'';
        $this->url = $args['url']??'lulu';
        $this->idusuarios = $args['idusuarios']??'';
    }


    public function validar_proyecto(){
        if(!$this->nombre){
            self::$alertas['error'][]= "El nombre del proyecto es obligatorio";
        }
        return self::$alertas;
    }
 }