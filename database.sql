
CREATE DATABASE IF NOT EXISTS clinica_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE clinica_db;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabla de pacientes
CREATE TABLE IF NOT EXISTS pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    fecha_nacimiento DATE NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    direccion TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS procedimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS historial (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    procedimiento_id INT NOT NULL,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    pieza_dental VARCHAR(20),
    observaciones TEXT,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE RESTRICT,
    FOREIGN KEY (procedimiento_id) REFERENCES procedimientos(id) ON DELETE RESTRICT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO procedimientos (nombre, descripcion, precio) VALUES
('Limpieza dental', 'Eliminación de placa y sarro', 30.00),
('Blanqueamiento', 'Aclarado del color de los dientes', 80.00),
('Extracción de muela', 'Extracción de pieza dental', 40.00),
('Extracción de cordal', 'Extracción de muela del juicio', 60.00),
('Tratamiento de conducto', 'Endodoncia', 120.00),
('Calza de muela', 'Restauración con resina', 35.00);

INSERT INTO pacientes (nombre, apellido, cedula, fecha_nacimiento, telefono, direccion) VALUES
('María', 'Gómez', 'V-12345678', '1990-05-14', '0414-1234567', 'Maracaibo, Zulia'),
('Pedro', 'Ramírez', 'V-23456789', '1985-11-02', '0424-7654321', 'San Francisco, Zulia');