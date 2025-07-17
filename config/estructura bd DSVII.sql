-- Crear base de datos
CREATE DATABASE IF NOT EXISTS PixelPerfect;

-- Usar la base de datos
USE PixelPerfect;

-- Tabla Usuarios (mejorada)
CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    correo_usuario VARCHAR(100) UNIQUE NOT NULL,
    pass_usuario VARCHAR(255) NOT NULL, -- Aumentado para hashes
    nombre_completo VARCHAR(150),
    telefono VARCHAR(20),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado_usuario ENUM('activo', 'inactivo', 'suspendido') DEFAULT 'activo',
    INDEX idx_correo (correo_usuario)
);

-- Tabla Categorias (nueva)
CREATE TABLE Categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL,
    descripcion TEXT,
    icono VARCHAR(50), -- Para iconos CSS o nombres de archivos
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla Servicios (mejorada)
CREATE TABLE Servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre_servicio VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_base DECIMAL(10,2),
    duracion_estimada INT, -- en minutos
    id_categoria INT,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria),
    INDEX idx_categoria (id_categoria),
    INDEX idx_activo (activo)
);

-- Tabla SolicitudServicio (mejorada)
CREATE TABLE SolicitudServicio (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_programada DATETIME,
    fecha_completado DATETIME,
    estado ENUM('pendiente', 'confirmado', 'en_proceso', 'completado', 'cancelado') DEFAULT 'pendiente',
    prioridad ENUM('baja', 'media', 'alta', 'urgente') DEFAULT 'media',
    notas_cliente TEXT,
    notas_admin TEXT,
    precio_final DECIMAL(10,2),
    id_usuario INT,
    id_servicio INT,
    direccion_servicio TEXT,
    telefono_contacto VARCHAR(20),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES Servicios(id_servicio),
    INDEX idx_usuario (id_usuario),
    INDEX idx_servicio (id_servicio),
    INDEX idx_estado (estado),
    INDEX idx_fecha_solicitud (fecha_solicitud)
);

-- Tabla Contacto (mejorada)
CREATE TABLE Contacto (
    id_contacto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_contacto VARCHAR(100) NOT NULL,
    correo_contacto VARCHAR(100) NOT NULL,
    telefono_contacto VARCHAR(20),
    asunto VARCHAR(200),
    mensaje TEXT NOT NULL,
    fecha_mensaje TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado_mensaje ENUM('nuevo', 'leido', 'respondido') DEFAULT 'nuevo',
    id_usuario INT NULL, -- Opcional, para usuarios registrados
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE SET NULL,
    INDEX idx_estado (estado_mensaje),
    INDEX idx_fecha (fecha_mensaje)
);

-- Tabla Historial_Estados (nueva - para auditoría)
CREATE TABLE Historial_Estados (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_solicitud INT,
    estado_anterior ENUM('pendiente', 'confirmado', 'en_proceso', 'completado', 'cancelado'),
    estado_nuevo ENUM('pendiente', 'confirmado', 'en_proceso', 'completado', 'cancelado'),
    fecha_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observaciones TEXT,
    FOREIGN KEY (id_solicitud) REFERENCES SolicitudServicio(id_solicitud) ON DELETE CASCADE,
    INDEX idx_solicitud (id_solicitud)
);

-- Tabla Calificaciones (nueva)
CREATE TABLE Calificaciones (
    id_calificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_solicitud INT,
    id_usuario INT,
    calificacion TINYINT CHECK (calificacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha_calificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_solicitud) REFERENCES SolicitudServicio(id_solicitud) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    UNIQUE KEY unique_calificacion_por_solicitud (id_solicitud),
    INDEX idx_calificacion (calificacion)
);