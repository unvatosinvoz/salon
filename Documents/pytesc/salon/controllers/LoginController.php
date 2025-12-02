<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Classes\Email;

class LoginController {
    public static function login(Router $router) {
        // Array para acumular mensajes de alerta (errores/éxitos)
        $alertas = [];

        // Solo procesar cuando el formulario se envía por POST
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Crear un objeto Usuario con los datos recibidos (sanitización/validación en el modelo)
            $auth = new Usuario($_POST);

            // Validar campos del formulario (email y password)
            $alertas = $auth->validarLogin();

            // Si no hay alertas, continuar con la autenticación
            if(empty($alertas)) {
                // Buscar en la base de datos un usuario con ese email
                $usuario = Usuario::where('email', $auth->email);

                if($usuario) {
                    // Verificar que la contraseña coincida y que la cuenta esté verificada
                    if( $usuario->comprobarPasswordAndVerificado($auth->password) ) {
                        // Iniciar la sesión y guardar datos relevantes en $_SESSION
                        

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionar según el rol (admin o usuario normal)
                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                        // Nota: no se hace exit() aquí porque las rutas/headers manejan la respuesta
                    }
                } else {
                    // Si no existe el usuario, registrar una alerta de error
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }

            }
        }

        // Obtener todas las alertas generadas (desde el modelo Usuario)
        $alertas = Usuario::getAlertas();
        
        // Renderizar la vista de login enviando alertas y título
        $router->render('auth/login', [
            'alertas' => $alertas,
            'titulo' => 'Iniciar Sesión',
            'auth' => $auth ?? null
        ]);
    }

    public static function logout() {
        // Iniciar sesión si no está activa
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Vaciar variables de sesión
        $_SESSION = [];
        session_unset();

        // Destruir la sesión en el servidor
        session_destroy();

        // Redirigir; si ya se enviaron cabeceras, usar fallback JS/meta
        $redirect = '/';
        if (!headers_sent()) {
            header('Location: ' . $redirect);
            exit;
        }

        // Fallback cuando no se pueden enviar cabeceras
        echo '<script>window.location.href="' . $redirect . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=' . $redirect . '" /></noscript>';
        exit;
    }

    public static function olvide(Router $router) {
        // Lógica para mostrar el formulario de recuperación de contraseña
        $alertas = [];
         if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();   
            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                // Verificar que el usuario exista y esté confirmado
                if($usuario && $usuario->confirmado === "1") {
                    // Generar un nuevo token y lo guarda en la base de datos
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Enviar el email con un token de recuperación
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarInstrucciones(); //TODO: crear este metodo en el Email.php

                    // Alerta de éxito
                    Usuario::setAlerta('exito', 'Revisa tu email para las instrucciones');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                }
            }
            $alertas = Usuario::getAlertas();
        }
        $router->render('auth/olvide-password', [
            'titulo' => 'Recuperar Password',
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        // Lógica para procesar la recuperación de contraseña
        $alertas = [];
        $error = false; //bandera para saber si hay un error con el token
        $token = s($_GET['token']); // Sanitizar el token recibido por GET
        
        if(!$token) header('Location: /');  
        // Buscar un usuario con ese token
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
            $alertas = Usuario::getAlertas();   
            
        } else {
            // Procesar el formulario cuando se envía por POST
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sincronizar el objeto usuario con los datos del formulario
                $usuario->sincronizar($_POST);
                // Validar el nuevo password
                $alertas = $usuario->validarPassword();
                if(empty($alertas)) {
                    // Hashear el nuevo password y eliminar el token
                    $usuario->hashPassword();
                    $usuario->token = null;
                    // Guardar los cambios en la base de datos
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        header('Location: /');     
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {
        // Lógica para mostrar el formulario de creación de cuenta

        $usuario = new Usuario();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar el formulario de creación de cuenta
            //echo "Procesar creación de cuenta";
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que el arreglo de alertas esté vacío
            if (empty($alertas)) {
                // Verificar que el usuario no esté registrado
                $resultado = $usuario->existeUsuario();
                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {              // Si No está registrado
                    // Hashear el password
                    $usuario->hashPassword();
                    // Generar un token único
                    $usuario->crearToken();
                    //enviar el email de confirmación
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();   
                    //debuguear($email);
                    //Crear el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        // Enviar el email de confirmación  
                        //echo "Usuario creado correctamente. Revisa tu email para confirmar tu cuenta.";
                        header('Location: /mensaje');
                    }
                    //debuguear($usuario);
                }
            }

        }
        // Enviamos el Usuario y. todas las alertas a la vista
        $router->render('auth/crear-cuenta', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario ?? null,
            'alertas' => $alertas ?? []
        ]);
    }

    public static function mensaje(Router $router) {
        // Lógica para mostrar el mensaje de cuenta creada
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada. Confirma tu Cuenta en tu Email'
        ]);
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        // Lógica para confirmar la cuenta del usuario
        $token = s($_GET['token']);
        if (!$token) 
            header('Location: /');

        // Encontrar el usuario con el token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            // Confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }

        // Obtener las alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'titulo' => 'Confirmar Cuenta',
            'alertas' => $alertas
        ]);
    }   
}