<?php 

require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/funciones.php';


use Controllers\LoginController;
use MVC\Router;
use Controllers\CitaController;
use Controllers\APIController;
use Controllers\AdminController;
use Controllers\ServicioController;

$router = new Router();

//Iniciar sesión y revisar si el usuario está autenticado
$router->get('/', [loginController::class, 'login']);
$router->post('/', [loginController::class, 'login']);
$router->get('/logout', [loginController::class, 'logout']);

// Rutas para recuperar Password
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);
$router->get('/recuperar', [LoginController::class, 'recuperar']);
$router->post('/recuperar', [LoginController::class, 'recuperar']);

// Rutas para Crear una cuenta
$router->get('/crear-cuenta', [LoginController::class, 'crear']);
$router->post('/crear-cuenta', [LoginController::class, 'crear']);  

// Rutas para confirmar cuenta
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']);
$router->get('/mensaje', [LoginController::class, 'mensaje']);

// rutas para el área privada
$router->get('/cita', [CitaController::class, 'index']);
$router->get('/admin', [AdminController::class, 'index']);




// rutas para la API de citas
$router->get('/api/servicios', [APIController::class, 'index']);
$router->post('/api/citas', [APIController::class, 'guardar']);
$router->post('/api/eliminar', [APIController::class, 'eliminar']);

// Rutas para el CRUD de servicios
$router->get('/servicios', [ServicioController::class, 'index']);        // R (Read) - listar servicios
$router->get('/servicios/crear', [ServicioController::class, 'crear']);  // C (Create) - formulario alta
$router->post('/servicios/crear', [ServicioController::class, 'crear']); // C (Create) - guardar nuevo
$router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']);  // U (Update) - formulario edición
$router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']); // U (Update) - guardar cambios
$router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']);     // D (Delete) - eliminar servicio
// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();