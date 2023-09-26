<?php

namespace Controllers;

use Model\usuarios; //namespace\clase hija
use MVC\Router;  //namespace\clase
use PHPMailer\PHPMailer\PHPMailer;
 
class login_controlador{

    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            //$_POST = ['email'=>'correo@correo.com', 'password'=>123456]
            $auth = new usuarios($_POST);  //$auth es objeto de la clase usuarios de los datos que el usuario escribio
            $alertas = $auth->validar_login();  //valida que los campos esten escritos
            if(empty($alertas)){

                //$resultado = $auth->validar_registro();  //valida si email existe? retorna 1 o 0 
                $resultado = $auth->find('email', $auth->email); //busca en la columna 'email' el correo electronico: $auth->email y retorna el registro de la bd en un objeto
                if($resultado){ //existe usuario o confirmado     //$resultado es objeto de la clase usuarios pero con los datos de la bd

                    $pass = $resultado->comprobar_password($auth->password);  //comprueba password y verifica si esta confirmado
                    if($pass){ 
                        //autenticar usuario 
                        session_start();
                        $_SESSION['id'] = $resultado->id;
                        $_SESSION['nombre'] = $resultado->nombre." ".$resultado->apellido;
                        $_SESSION['email'] = $resultado->email;
                        $_SESSION['login'] = true;
                        //redireccionar

                              //cliente
                            header('Location: /dashboard');       

                            }
                }else{
                    $alertas = usuarios::setAlerta('error', 'usuario no encontrado o no existe');
             
                }
            } //cierre del empty de alertas
        } //cierre de $_SERVER['REQUEST_METHOD'] === 'POST'

        $alertas = usuarios::getAlertas();
       $router->render('autenticacion/login', ['alertas'=>$alertas, 'titulo'=>'iniciar sesion']);   //  'autenticacion/login' = carpeta/archivo
    }



    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }



    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new usuarios($_POST);
            $alertas = $auth->validar_email();
            if(empty($alertas)){
                $auth = usuarios::find('email', $auth->email);
                if($auth && $auth->confirmado === '1'){ //si existe registro y si el campo confirmado es uno '1'
                    //generar token
                    $auth->creartoken();
                    $auth->actualizar();

                    //enviar email
                    $mail = new PHPMailer();
                    //configar SMTP
                    $mail->isSMTP();
                    $mail->Host = "smtp.mailtrap.io";
                    $mail->SMTPAuth = true;
                    $mail->Username = "16acf5de9a6d51";
                    $mail->Password = "5920be5576bd6b";
                    $mail->SMTPSecure = "tls";
                    $mail->Port = 2525;
                    //configurar el contenido del email
                    $mail->setFrom('cuentas@UpTask.com'); //quien envia el email
                    $mail->addAddress('cuentas@appsalon.com', 'UpTask.com');  //a quien llega el email
                    $mail->Subject = "Restablece tu password";
                    //habilitar html
                    $mail->isHTML(true);
                    $mail->CharSet = "UTF-8";
                    //contenido
                    $contenido = "<html>"; 
                    $contenido .= "<p><strong> hola $auth->nombre </strong>has solicitado restablecer tu password sigue el siguiente enlace para hacerlo</p>";
                    $contenido .= "<p>Presiona Aqui: <a href='http://uptask_mvc.test/recuperar?token=".$auth->token."'>Recuperar password</a> </p>";
                    $contenido .= "<p> Si tu no solicitaste esta cuenta, puedes ignorar el mensaje </p>";
                    $contenido .= "</html>"; 

                    $mail->Body = $contenido;
                    $mail->AltBody = "texto alternativo";  //cunado no soporta html el servicio de email

                    //enviar email
                    if($mail->send()){
                        $mensaje = "enviado corretamente sin html";
                    }else{
                        $mensaje = "Soliitud no enviado";
                         }

                    usuarios::setAlerta('exito', 'revisa tu email');
                    

                }else{
                    usuarios::setAlerta('error', 'el usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = usuarios::getAlertas();
        $router->render('autenticacion/olvide', ['alertas'=>$alertas, 'titulo'=>'recuperar']);
    }



    public static function recuperar(Router $router){

        $alertas = [];
        $error = false;

        $token = s($_GET['token']); //token de la url
        //buscar usuario por su token
        $resultado = usuarios::find('token', $token);  //instancia con los datos de la bd
        if(empty($resultado)){
            usuarios::setAlerta('error', 'token no valido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //leer el nuevo password y guardarlo
            $new_password = new usuarios($_POST); //instancia solo con los datos del formulario
            $alertas = $new_password->validar_password();
            if(empty($alertas)){
                $resultado->password = null;
                $resultado->password = $new_password->password;  //la propiedad password de la instancia u objeto resultado toma el valor de la propiedad password del objeto o instancia new_password.
                $resultado->hashearpass();
                $resultado->token = null;
                
                if($resultado->actualizar()){
                    header('Location: /');  }  
                
            }
        }
        $alertas = usuarios::getAlertas();
        $router->render('autenticacion/recuperar_password', ['alertas'=>$alertas, 'error'=>$error, 'titulo'=>'recuperar']);
    }



    public static function crear_cuenta(Router $router){

        $usuario = new usuarios; //instancia el objeto vacio
        $alertas = [];  //alertas vacias
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            
            $usuario->compara_objetobd_post($_POST); //objeto instaciado toma los valores del $_POST
            $alertas = $usuario->validar_nueva_cuenta(); //metodo de mensajes de validacion para la creacion de la cuenta, nombre, apellido etc..
            //revisar que las alertas esten vacios
            if(empty($alertas)){ //si el arreglo alertas esta vacio es pq paso las validacion del formulario crear cuenta
                
                $usuarioexiste = $usuario->validar_registro();//retorn 1 si existe usuario(email), 0 si no existe
                if($usuarioexiste){ //si usuario ya existe
                    $usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = $usuario::getAlertas();
                }else{
                    //hashear pass
                    $usuario->hashearpass();
                    //eliminar pass2
                    unset($usuario->password2);
                    //generar token
                    $usuario->creartoken();
                

                    //enviar el email
                    $mail = new PHPMailer();

                    //configar SMTP
                    $mail->isSMTP();
                    $mail->Host = "smtp.mailtrap.io";
                    $mail->SMTPAuth = true;
                    $mail->Username = "16acf5de9a6d51";
                    $mail->Password = "5920be5576bd6b";
                    $mail->SMTPSecure = "tls";
                    $mail->Port = 2525;

                    //configurar el contenido del email
                    $mail->setFrom('cuentas@UpTask.com'); //quien envia el email
                    $mail->addAddress('cuentas@appsalon.com', 'UpTask.com');  //a quien llega el email
                    $mail->Subject = "confirma tu cuenta";

                    //habilitar html
                    $mail->isHTML(true);
                    $mail->CharSet = "UTF-8";

                    //contenido
                    $contenido = "<html>"; 
                    $contenido .= "<p><strong> hola $usuario->nombre </strong>has creado tu cuenta en UpTask.com, solo debes confirmarla presionado el siguiente enlace</p>";
                    $contenido .= "<p>Presiona Aqui: <a href='http://uptask_mvc.test/confirmar_cuenta?token=".$usuario->token."'>Confirmar Cuenta</a> </p>";
                    $contenido .= "<p> Si tu no solicitaste esta cuenta, puedes ignorar el mensaje </p>";
                    $contenido .= "</html>"; 


                    $mail->Body = $contenido;
                    $mail->AltBody = "texto alternativo";  //cunado no soporta html el servicio de email

                    //enviar email
                    
                    if($mail->send()){
                        $mensaje = "enviado corretamente sin html";
                    }else{
                        $mensaje = "Soliitud no enviado";
                         }

                //guardar cliente recien creado en bd  
                $resultado = $usuario->crear_guardar();  
                if($resultado){
                    header('Location: /mensaje');
                }     

                }
            }
        } //cierre del if $_SERVER[REQUEST_METHOD]
       
        $router->render('autenticacion/crear_cuenta', ['usuario'=>$usuario, 'alertas'=>$alertas, 'titulo'=>'crear cuenta']);
    } //cierre del metodo



    public static function mensaje(Router $router){
        $router->render('autenticacion/mensaje', ['titulo'=>'mensaje']);
    } 



    public static function confirmar_cuenta(Router $router){ //este metodo se llama cuando el usuario confirma la url enviada al correo electroico
        $alertas = [];
        $token  = s($_GET['token']);  //con el metodo get se lee los datos de url superior
        
        $resultado = usuarios::find('token', $token); //encuentra usuario por token
        if(empty($resultado)){
            usuarios::setAlerta('error', 'token no valido'); 

        }else{  //usuario encontrado, se debe cambiar token para que el token no sea mas valido, lo que se hace es validar que el correo si exista y no sea inventado
             $resultado->confirmado = '1';
             $resultado->token = null;
             //actualizar bd con el objeto en memoria ya modificado
             $resultado->actualizar();
             usuarios::setAlerta('exito', 'cuenta comprobada correctamente');  //cuenta confirmado y correo validado 
        }

        $alertas = usuarios::getAlertas();
        $router->render('autenticacion/confirmar_cuenta', ['alertas'=>$alertas, 'titulo'=>'confirma cuenta']);
    }

    
    
} //cierre de la clase
