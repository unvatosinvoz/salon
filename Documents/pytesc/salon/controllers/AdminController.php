<?php

namespace Controllers;

use MVC\Router;
use Model\AdminCita;

class AdminController {
   public static function index(Router $router) {
       // Lógica para manejar la vista de administración
       
        // Iniciar sesión si no está activa
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        isAuth(); // Asegura que el usuario esté autenticado

        // Consulta SQL para obtener las citas con detalles de usuario y servicio

        $fecha = $_GET['fecha'] ?? date('Y-m-d'); //obtenemos la fecha actual
        $fechas = explode('-', $fecha);
        if(!checkdate($fechas[1], $fechas[2], $fechas[0])) {
            header('Location: /404');
        } 

        //Observaras esta consulta que tiene varios JOINs para traer la informacion relacionada
        $sql = "SELECT Citas.id, Citas.hora, CONCAT(Usuarios.nombre, ' ', Usuarios.apellido) as cliente, \n"
            . "    Usuarios.email, Usuarios.telefono, Servicios.nombre as servicio, Servicios.precio\n"
            . "  FROM `Citas` \n"
            . "    LEFT OUTER JOIN Usuarios \n"
            . "      ON citas.usuarioId=Usuarios.id \n"
            . "    LEFT OUTER JOIN CitasServicios \n"
            . "      ON CitasServicios.citaId=Citas.id \n"
            . "    LEFT OUTER JOIN Servicios \n"
            . "      ON Servicios.id=CitasServicios.servicioId\n"
            . "  WHERE fecha = '{$fecha}';";

        $citas = AdminCita::SQL($sql);
        //debuguear($citas);

        $router->render('admin/index', [
            'nombre' => 'Panel de Administración',
            'nombre_admin' => $_SESSION['nombre'] ?? '',
            'citas' => $citas,
            'fecha' => $fecha
        ]);
   }
}