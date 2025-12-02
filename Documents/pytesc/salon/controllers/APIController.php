<?php
namespace Controllers;

use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all();
        header('Content-Type: application/json; charset=utf-8');

        // Si tu ActiveRecord devuelve objetos, asegúrate de serializarlos a array
        $servicios = Servicio::all(); // Debe devolver array de objetos/arrays

        echo json_encode($servicios, JSON_UNESCAPED_UNICODE);
    }

    public static function guardar() {
        // Almacena la Cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $id = $resultado['id'] ?? null;
        // Almacena los Servicios con el ID de la Cita
        if ($id && isset($_POST['servicios'])) {
            $idServicios = explode(",", $_POST['servicios']);
            foreach ($idServicios as $idServicio) {
                $args = [
                    'citaId' => $id,
                    'servicioId' => $idServicio
                ];
                // Cuando tengas el modelo CitaServicio, descomenta estas

                 $citaServicio = new CitaServicio($args);
                 $citaServicio->guardar();
            }
        }
        // Responder con un mensaje de éxito
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];

        // Eliminar la cita
        $cita = Cita::find($id);
        //echo json_encode($cita);
        if($cita) {
            $cita->eliminar();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['resultado' => 'ok']);
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['resultado' => 'error', 'mensaje' => 'Cita no encontrada']);
        }
    }
}  
}

