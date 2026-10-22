CREATE DATABASE semana11_crud
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE semana11_crud;

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(60) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO productos (nombre, categoria, precio, stock) VALUES
('Teclado mecanico', 'Accesorios', 25000.00, 12),
('Mouse inalambrico', 'Accesorios', 12000.00, 20),
('Monitor de 24 pulgadas', 'Monitores', 85000.00, 5),
('Audifonos USB', 'Audio', 18000.00, 8),
('Base para laptop', 'Accesorios', 9500.00, 15);