<?php
namespace Model;
class Cita extends ActiveRecord {
    // Nombre de la tabla
    protected static $tabla = 'Citas';
    // Columnas de la tabla
    protected static $columnasDB = ['id', 'fecha', 'hora', 'usuarioId'];
    public $id;
    public $fecha;
    public $hora;
    public $usuarioId;
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
    $this->hora = $args['hora'] ?? '';
    $this->usuarioId = $args['usuarioId'] ?? null;
    }
// Aquí puedes agregar validaciones, relaciones y otros métodos
}