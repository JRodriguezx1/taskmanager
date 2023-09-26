<?php
namespace Model;
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $alertas = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Validación
    public static function getAlertas() {
        return static::$alertas;
    }

    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    public function eliminar_registro(){
        $sqldelete = "DELETE FROM ".static::$tabla." WHERE id = ".self::$db->escape_string($this->id)." LIMIT 1";
        //debuguear($this);  $this tiene el objeto actual
        $resultado = self::$db->query($sqldelete);
        return $resultado;
    }  


    public function crear_guardar(){
        $atributos = $this->sanitizar_datos();  //$atributos obtiene arreglo ['titulo'=>valor1, 'precio'=>valor2] 
        $string1 = join(', ', array_keys($atributos));  //array_keys  = ['llave1', 'llave2', 'llave3', ....]
        $string2 = join("', '", array_values($atributos)); // array_values = [valor1', 'valor2', 'valor3', '....]
        $sql = "INSERT INTO ".static::$tabla."(";
        $sql .= $string1;
        $sql .= ") VALUES('";
        $sql .= $string2;
        $sql .= "');";
        $resultado = self::$db->query($sql);
        return [$resultado, self::$db->insert_id];  //insert_id retorna el ultimo registro insertado en la bd
           //  [true/false, id=1,2,3...00] = [0,1]
        
        
    }


    public function actualizar(){
        $atributos = $this->sanitizar_datos(); 
        $valores = [];
        foreach($atributos as $key => $value ){
            if($key === "fechacreacion")$value = date('y-m-d'); //
            $valores[] = "{$key}='{$value}'"; //$valores = [llave1='valor1', llave2='valor2',...]
        }
        $query = "UPDATE ".static::$tabla." SET ";
        $query .= join(", ", $valores); // = "llave1='valor1', llave2=>valor2,...."
        $query .= " WHERE id = '".self::$db->escape_string($this->id)."'";
        $query .= " LIMIT 1;";
        $resultado = self::$db->query($query);
        return $resultado;
    }


    public function actualizar_referencia($copyreferencia){   //actualiza el id, referencia o llave primaria
        $atributos = $this->sanitizar_datos(); 
        $valores = [];
        foreach($atributos as $key => $value ){
            if($key === "fechacreacion")$value = date('y-m-d'); //
            $valores[] = "{$key}='{$value}'"; //$valores = [llave1='valor1', llave2='valor2',...]
        }
        $query = "UPDATE ".static::$tabla." SET ";
        $query .= join(", ", $valores);
        $query .= " WHERE Referencia = '".self::$db->escape_string($copyreferencia)."'";
        $query .= " LIMIT 1;";
        $resultado = self::$db->query($query);
        return $resultado;
    }


    public function sanitizar_datos(){
        $atributos = $this->atributos();  //devuelve arreglo asociativo ['titulo'=>valor_titulo, 'precio'=>valor_precio]
        $sanitizado = [];
        foreach($atributos as $key => $value){
            $sanitizado[$key] = self::$db->escape_string($value);  //arreglo sanitizado ['titulo'=>valor_titulo, 'precio'=>valor_precio] pero protegido pra inyecciones sql
        }
        return $sanitizado;  //se retorna arreglo[] ya sanitizado
    }


    public function atributos(){
        $atributos = [];
        foreach(static::$columnasDB as $columna){  //el arreglo $columnas_db toma valores de la clase hija
            if($columna == 'id') continue;  
            $atributos[$columna] = $this->$columna;    //columna va iterando y va tomando valores como 'titulo', 'precio'
        }      
        return $atributos;  //retorna arreglo asociativo ['titulo'=>$this->titulo, 'precio'=>$this->precio]
    }


    public static function all(){ //se trae todos los registros de la tabla de la bd
        $sql = "SELECT *FROM ".static::$tabla;  //accede al atributo protegido, static es un modificador de acceso, static busca el atributo tabla en la clase el cual se hereda
        $resultado = self::consultar_Sql($sql); 
        return $resultado;  //$resultado es un arreglo de objetos
    }


    public static function get($cantidad){  
        $sql = "SELECT *FROM ".static::$tabla." LIMIT ".$cantidad;
        $resultado = self::consultar_sql($sql);  //self hace referencia a la clase padre, self solo funciona dentro de metodos
        return $resultado;  //$resultado es arreglo de objetos
    }


    public static function filtro_nombre($nombre){   //metodo que trae los registros con el nombre especificado
        $sql = "SELECT *FROM ".static::$tabla." WHERE Nombre LIKE "."'{$nombre}%'"." ORDER BY Precio DESC;";
        $resultado = self::consultar_sql($sql);
        return $resultado;
    }


    public static function idusuario($colum, $id){ ////metodo que busca todos los registro qu pertenecen a un id
        $sql = "SELECT *FROM ".static::$tabla." WHERE $colum = '${id}';";
        $resultado = self::consultar_Sql($sql);
        return $resultado;
    }


    //busca un solo registro por su id
    public static function find($colum, $id){
        $sql = "SELECT *FROM ".static::$tabla." WHERE $colum = '${id}' LIMIT 1;";
        $resultado = self::consultar_Sql($sql);
        return array_shift($resultado); //array_shift retorna el primer elemento del arreglo
    }


    public static function inner_join($consulta){
        $resultado = self::consultar_sql($consulta);
        return $resultado;
    }


    public function validar_registro(){
        $sql = "SELECT * FROM ".static::$tabla." WHERE email = '".$this->email."' LIMIT 1;";
        $resultado = self::$db->query($sql);
        return $resultado->num_rows;  //si hay registro num_rows = 1, si no hay registro num_rows = 0
    }
    

    public static function consultar_sql($sql){
        $resultado = self::$db->query($sql);  // consulta la bd se trae toda la tabla 
        $array = []; 
        while($row = $resultado->fetch_assoc()){  //$row obtiene un registro a la vez desde el primero de la tabla de la bd
            $array[] = self::crear_objeto($row);   //Row = ['id'=>1, 'titulo'=>'xxxx'...] $row es un arreglo asociativo
        }
        $resultado->free();
        return $array;  //$array es un arreglo de objetos
    }


    public static function crear_objeto($row){
        $objeto = new static;  //crea un objeto con los atributos vacios y el tipo depende si se llamada del padre o del hijo
        foreach($row as $key => $value){  //Row es un arreglo asociativo donde las llaves son los atributos del objeto o clase
            if(property_exists($objeto, $key)){   // Esta función comprueba si la propiedad dada por $key existe en la clase o objeto especificada
                $objeto->$key = $value;
            }
        } 
        return $objeto;  //retorna el nuevo objeto creado dinamicamneto con los atributos llenos donde los atriburos son los campos de la bd..
    }


    //compara el objeto creado con los valores del $_POST, y remplaza solo los valores del $post
    public function compara_objetobd_post($args){
        foreach($args as $key => $value){      //$key = a la llave del arreglo asociativo y $value es el valor de esa llave del arreglo asociativo
            if(property_exists($this, $key) && !is_null($value)){   //property_exist revisa de un objeto que un atributo exista
               $this->$key = $value;                 //$this hace referencia al objeto actual, objeto va tomando los valores del $_POST                
            }
        }  
    }

    
}