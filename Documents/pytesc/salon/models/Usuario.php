<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'Usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'telefono', 'email', 'password', 'token', 'admin', 'confirmado'];

    public $id;
    public $nombre;
    public $apellido;
    public $telefono;
    public $email;
    public $password;
    public $admin;
    public $token;
    public $confirmado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // Validación para nueva cuenta
    public function validarNuevaCuenta() {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio'; 
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es Obligatorio'; 
        }
        if (!$this->telefono) {
            self::$alertas['error'][] = 'El Teléfono es Obligatorio'; 
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio'; 
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if( strlen($this->password) < 6 ) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }   
        return self::$alertas;
    }

    // Validación para login
    public function validarLogin() {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio'; 
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        return self::$alertas;
    }
    // Validación para email
    public function validarEmail() {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio'; 
        }
        return self::$alertas;
    }
    // Validación para password
    public function validarPassword() {
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }
        if( strlen($this->password) < 6 ) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }   
        return self::$alertas;
    }

    // Revisar si el usuario ya existe
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1;";
        $resultado = self::$db->query($query);  
        //debuguear($query);
        //debuguear($resultado);
        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'El Usuario ya está registrado';
        }
        return $resultado;
    }

    // Hashear el password
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);  
    }

    // Generar un token único
    public function crearToken() {
        $this->token = uniqid();    
    }
    
    // comprobar password y usuario confirmado
    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);
        if(!$resultado || !$this->confirmado ) {
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }       

}
