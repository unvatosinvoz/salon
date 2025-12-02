-- =========================================================
-- CREACIÓN DE BASE DE DATOS appSalon
-- =========================================================
CREATE DATABASE IF NOT EXISTS appSalon
  CHARACTER SET utf8
  COLLATE utf8_general_ci;

USE appSalon;

-- =========================================================
-- TABLA: Usuarios
-- Contiene la información de los clientes y administradores.
-- =========================================================
CREATE TABLE IF NOT EXISTS Usuarios (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(60) NOT NULL,
    apellido VARCHAR(60) NOT NULL,
    email VARCHAR(30) NOT NULL UNIQUE,
    telefono VARCHAR(10),
    admin TINYINT(1) DEFAULT 0,
    confirmado TINYINT(1) DEFAULT 0,
    token VARCHAR(15),
    CONSTRAINT usuarios_pk PRIMARY KEY (id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8
  COLLATE=utf8_general_ci;

-- =========================================================
-- TABLA: Servicios
-- Lista los servicios disponibles en el salón.
-- =========================================================
CREATE TABLE IF NOT EXISTS Servicios (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(60) NOT NULL,
    precio DECIMAL(5,2) NOT NULL,
    CONSTRAINT servicios_pk PRIMARY KEY (id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8
  COLLATE=utf8_general_ci;

-- =========================================================
-- TABLA: Citas
-- Contiene la información de las citas agendadas por usuario.
-- Cada cita pertenece a un usuario.
-- =========================================================
CREATE TABLE IF NOT EXISTS Citas (
    id INT(11) NOT NULL AUTO_INCREMENT,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    usuarioId INT(11) NOT NULL,
    CONSTRAINT citas_pk PRIMARY KEY (id),
    CONSTRAINT fk_citas_usuario
        FOREIGN KEY (usuarioId)
        REFERENCES Usuarios(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8
  COLLATE=utf8_general_ci;

-- =========================================================
-- TABLA: CitasServicios
-- Relación muchos-a-muchos entre Citas y Servicios.
-- =========================================================
CREATE TABLE IF NOT EXISTS CitasServicios (
    id INT(11) NOT NULL AUTO_INCREMENT,
    citaId INT(11) NOT NULL,
    servicioId INT(11) NOT NULL,
    CONSTRAINT citasservicios_pk PRIMARY KEY (id),
    CONSTRAINT fk_citasservicios_cita
        FOREIGN KEY (citaId)
        REFERENCES Citas(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_citasservicios_servicio
        FOREIGN KEY (servicioId)
        REFERENCES Servicios(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8
  COLLATE=utf8_general_ci;