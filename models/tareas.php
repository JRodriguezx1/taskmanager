<?php

namespace Model;

use Model\ActiveRecord;

 class tareas extends ActiveRecord{
     protected static $tabla = 'tareas';
     protected static $columnasDB = ['id', 'nombre', 'estado', 'idproyectos'];

     public function __construct($args = [])
    {
        $this->id = $args['id']??null;
        $this->nombre = $args['nombre']??'';
        $this->estado = $args['estado']??0;
        $this->idproyectos = $args['idproyectos']??'';
    }


 }