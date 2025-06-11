
-- Crear base de datos (opcional)
CREATE DATABASE PaginaServicios;
GO

-- Usar la base de datos recién creada
USE PaginaServicios;
GO

-- Tabla Usuarios
CREATE TABLE Usuarios (
    correo_usuario VARCHAR(100) PRIMARY KEY,
    pass_usuario VARCHAR(100) NOT NULL
);

-- Tabla Servicios (lista fija de servicios ofrecidos)
CREATE TABLE Servicios (
    id_servicio INT PRIMARY KEY IDENTITY(1,1),
    nombre_servicio VARCHAR(100) NOT NULL,
    descripcion TEXT
);

-- Tabla SolicitudServicio
CREATE TABLE SolicitudServicio (
    id_ss INT PRIMARY KEY IDENTITY(1,1),
    fecha_solicitud DATETIME NOT NULL DEFAULT GETDATE(),
    estado VARCHAR(20) CHECK (estado IN ('pendiente', 'en_proceso', 'completado', 'cancelado')) NOT NULL,
    user_usuario VARCHAR(100),
    id_servicio INT,
    FOREIGN KEY (user_usuario) REFERENCES Usuarios(correo_usuario),
    FOREIGN KEY (id_servicio) REFERENCES Servicios(id_servicio)
);

-- Tabla Contacto
CREATE TABLE Contacto (
    id_contacto INT PRIMARY KEY IDENTITY(1,1),
    correo_usuario VARCHAR(100),
    mensaje TEXT NOT NULL,
    FOREIGN KEY (correo_usuario) REFERENCES Usuarios(correo_usuario)
);
