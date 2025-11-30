CREATE DATABASE plataforma_academica;
USE plataforma_academica;

-- ROLES Admin, Maestro y Estudiante!
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL
);

INSERT INTO roles (id, nombre) VALUES (1, 'Administrador'), (2, 'Maestro'), (3, 'Estudiante');

-- Usuarioss
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- Usuario Admin por default Pass: admin123
-- generar el hash real
INSERT INTO usuarios (nombre, apellidos, email, password, rol_id) 
VALUES ('Super', 'Admin', 'admin@escuela.com', '$2y$10$e4g...', 1);

-- Materias-> Maestros
CREATE TABLE materias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    maestro_id INT NOT NULL,
    clave_acceso VARCHAR(20), -- Opcional, para colocar una contraseña a la materia
    FOREIGN KEY (maestro_id) REFERENCES usuarios(id)
);

-- Incribirse  "Pendiente/Aprobado"
CREATE TABLE inscripciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    alumno_id INT NOT NULL,
    materia_id INT NOT NULL,
    estado ENUM('Pendiente', 'Aprobado', 'Rechazado') DEFAULT 'Pendiente',
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (alumno_id) REFERENCES usuarios(id),
    FOREIGN KEY (materia_id) REFERENCES materias(id)
);

-- Tareas ctividades
CREATE TABLE tareas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    materia_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_limite DATE,
    ponderacion DECIMAL(5,2), -- el valor de la tarea (0-100%)
    FOREIGN KEY (materia_id) REFERENCES materias(id)
);

-- enviados y calif
CREATE TABLE entregas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tarea_id INT NOT NULL,
    alumno_id INT NOT NULL,
    archivo VARCHAR(255), -- URL del archivo ya subido
    calificacion DECIMAL(5,2), -- Calificación ya asignada
    comentarios TEXT, -- comentaios del profe
    fecha_entrega DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tarea_id) REFERENCES tareas(id),
    FOREIGN KEY (alumno_id) REFERENCES usuarios(id)
);