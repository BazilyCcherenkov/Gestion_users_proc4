-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS admin_usuarios;
USE admin_usuarios;

-- Crear la tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear la tabla de roles
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
);

-- Crear la tabla intermedia usuario_rol para relación N:N
CREATE TABLE IF NOT EXISTS usuario_rol (
    usuario_id INT,
    rol_id INT,
    PRIMARY KEY (usuario_id, rol_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Insertar datos de prueba en la tabla usuarios
INSERT INTO usuarios (nombre, correo, password) VALUES
('Juan Pérez', 'juan@example.com', SHA2('juan123', 256)),
('Ana Gómez', 'ana@example.com', SHA2('ana123', 256)),
('Carlos Ruiz', 'carlos@example.com', SHA2('carlos123', 256));

-- Insertar datos de prueba en la tabla roles
INSERT INTO roles (nombre, descripcion) VALUES
('admin', 'Acceso total al sistema'),
('editor', 'Puede modificar contenidos'),
('lector', 'Solo puede visualizar contenidos');

-- Asignar roles a usuarios en la tabla usuario_rol
INSERT INTO usuario_rol (usuario_id, rol_id) VALUES
(1, 1),  -- Juan es admin
(2, 2),  -- Ana es editor
(3, 3),  -- Carlos es lector
(2, 3);  -- Ana también puede ver contenidos
