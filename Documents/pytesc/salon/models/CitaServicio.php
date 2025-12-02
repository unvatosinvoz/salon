<?php

namespace Model;

/**
 * Clase CitaServicio
 *
 * Representa la relación entre una Cita y un Servicio dentro del modelo de datos.
 * Actúa como modelo ActiveRecord para la tabla de asociación (pivot) que vincula
 * citas con los servicios solicitados en cada una de ellas.
 *
 * Responsabilidades principales:
 * - Persistir la asociación entre una cita y un servicio.
 * - Proveer acceso a los atributos de la relación (por ejemplo, identificadores,
 *   precio aplicado al servicio, cantidad, etc.).
 * - Contener validaciones y lógica específica de la asociación (por ejemplo,
 *   comprobaciones de integridad referencial, reglas de negocio para precios).
 *
 * Uso típico:
 * - Crear una nueva asociación entre cita y servicio.
 * - Consultar los servicios asociados a una cita concreta.
 * - Eliminar o actualizar asociaciones cuando se modifican los detalles de la cita.
 *
 * Notas de implementación:
 * - Hereda de ActiveRecord, por lo que debe respetar las convenciones de mapeo
 *   de esta base (nombres de tabla/columnas, claves primarias/foráneas).
 * - Se recomienda definir reglas de validación para los campos clave
 *   (p. ej. cita_id, servicio_id) y cualquier campo adicional relevante
 *   (precio, duración, cantidad).
 *
 * Ejemplo:
 *   $citaServicio = new CitaServicio([
 *       'cita_id'     => $citaId,
 *       'servicio_id' => $servicioId,
 *       'precio'      => $precioAplicado
 *   ]);
 *   $citaServicio->guardar();
 */
class CitaServicio extends ActiveRecord {
    protected static $tabla = 'CitasServicios';
    protected static $columnasDB = ['id', 'citaId', 'servicioId'];

    public $id;
    public $citaId;
    public $servicioId;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->citaId = $args['citaId'] ?? '';
        $this->servicioId = $args['servicioId'] ?? '';
    }
}