CREATE DATABASE curso_php
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE curso_php;

CREATE TABLE estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO estudiantes (nombre, correo) VALUES
('Ana López', 'ana@correo.com'),
('Carlos Mora', 'carlos@correo.com'),
('María Solís', 'maria@correo.com');