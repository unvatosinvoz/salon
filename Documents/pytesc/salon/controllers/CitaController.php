<?php

namespace Controllers;
use MVC\Router;

class CitaController {
    
    public static function index(Router $router) {
        // Lógica para manejar la vista de citas
        isAuth();
        //session_start();

        $router->render('cita/index', [
            'nombre' => 'Crear Cita',
            'cliente' => $_SESSION['nombre'] ?? null,
            'id' => $_SESSION['id'] ?? null
        ]);
    }

    public static function admin() {
        // Lógica para manejar la vista de administración
    }

    
}