<?php

 namespace Model;
 
 class usuarios extends ActiveRecord{  //modelo o clase para la creacion o login de cuenta
     
     protected static $tabla = 'usuarios';
     protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

     public $x;
     protected static $y;

     public $id;
     public $nombre;
     public $email;
     public $password;
     public $token;
     public $confirmado;
     

     public function __construct($args = [])
     {
         $this->id = $args['id']??null;
         $this->nombre = $args['nombre']??'';
         $this->email = $args['email']??'';
         $this->password = $args['password']??'';
         $this->password2 = $args['password2']??'';
         $this->token = $args['token']??'';
         $this->confirmado = $args['confirmado']??0;
     }


     //mensajes de validacion para la creacion de la cuenta
     public function validar_nueva_cuenta(){
         if(!$this->nombre){
             self::$alertas['error'][]= "El nombre del cliente es obligatorio";
         }
        if(!$this->email){
            self::$alertas['error'][]= "El email es obligatorio";
        }
        if(!$this->password){
           self::$alertas['error'][]= "El password es obligatorio"; //['error'=>['string1', 'string2'...], 'key2'=>[]]
       }
       if(strlen($this->password)<6){
           self::$alertas['error'][]= "El password debe contener al menos 6 caracteres";

       }
       if($this->password != $this->password2){
        self::$alertas['error'][]= "Los passwords son diferentes";

       }


         return self::$alertas;

         self::$x = "";
         self::$y = 22;
     }



     public function validar_login(){
         if(!$this->email){
            self::$alertas['error'][]= "El email es obligatorio";
         }
         if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][]= "Email no valido";
        }
         if(!$this->password){
            self::$alertas['error'][]= "El password es obligatorio";   
        }
        return self::$alertas;
     }



     public function validar_email(){
        if(!$this->email){
            self::$alertas['error'][]= "El email es obligatorio";
         }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][]= "Email no valido";
        }
         return self::$alertas;
     }



     public function validar_password():array{  //retorna arreglo
        if(!$this->password){
            self::$alertas['error'][]= "El password es obligatorio";   
        }
        if(strlen($this->password)<6){
            self::$alertas['error'][]= "El password debe contener al menos 6 caracteres";
 
        }
        return self::$alertas;
     }


     
     public function validar_perfil(){
        if(!$this->nombre){
            self::$alertas['error'][]= "El nombre del cliente es obligatorio";
        }
        if(!$this->email){
            self::$alertas['error'][]= "El email es obligatorio";
         }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][]= "Email no valido";
        }
         return self::$alertas;
     }


      //metodo que comprueba el pass de la bd con el ingresado en el formulario en el login
     public function comprobar_password($password):array|bool{  //este metodo es llamado por el objeto $resultado que contiene los datos de la bd, que esta en el metodo login de la clase login_controller
         $resultado = password_verify($password, $this->password); //retorna true o false

         if(!$resultado || !$this->confirmado){  //si $resultado o this->confirmado es cero '0' es por pass incorrecto o no esta confirmado.
             self::$alertas['error'][] = "Password incorrecto o tu cuenta no ha sido confirmada"; 
         }
         else{
             return true;   //passwor correcto y confirmado
         }

     }



     public function hashearpass():void{   //:void no retorna nada
         $this->password = password_hash($this->password, PASSWORD_BCRYPT);
     }

     

     public function creartoken():void{
         $this->token = uniqid();
     }

 }