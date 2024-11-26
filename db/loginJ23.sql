CREATE DATABASE AD_J23;
USE AD_J23;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    apellido_usuario VARCHAR(50) NOT NULL,
    username_usuario VARCHAR(20) NOT NULL UNIQUE,
    password_usuario CHAR(64) NOT NULL,
    tipo_usuario ENUM('Alumno', 'Administrador', 'Conserjeria') NOT NULL DEFAULT 'Alumno',
    DNI_usuario CHAR(9) NOT NULL UNIQUE,
    correo_usuario VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla de cursos
CREATE TABLE cursos (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    nombre_curso VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla de alumnos
CREATE TABLE alumnos (
    id_alumno INT AUTO_INCREMENT PRIMARY KEY,
    nombre_alumno VARCHAR(50) NOT NULL,
    apellido_alumno VARCHAR(50) NOT NULL,
    correo_alumno VARCHAR(50) NOT NULL UNIQUE,
    id_curso INT NOT NULL,
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) 
);

-- Tabla de asignaturas
CREATE TABLE asignaturas (
    id_asignatura INT AUTO_INCREMENT PRIMARY KEY,
    nombre_asignatura VARCHAR(50) NOT NULL UNIQUE,
    id_curso INT NOT NULL,
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) 
);

-- Tabla de notas
CREATE TABLE notas (
    id_nota INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_asignatura INT NOT NULL,
    nota DECIMAL(4, 2) NOT NULL,
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno),
    FOREIGN KEY (id_asignatura) REFERENCES asignaturas(id_asignatura)
);

-- Inserts para la tabla cursos
INSERT INTO cursos (nombre_curso) VALUES
('ASIX2'),
('EASX2'),
('DAW2'),
('DAM2');

-- Inserts para la tabla alumnos
INSERT INTO alumnos (nombre_alumno, apellido_alumno, correo_alumno, id_curso) VALUES
('Ana', 'García', 'ana.garcia@escuela.edu', 1),
('Luis', 'Martínez', 'luis.martinez@escuela.edu', 2),
('María', 'López', 'maria.lopez@escuela.edu', 3),
('Carlos', 'Hernández', 'carlos.hernandez@escuela.edu', 4),
('Laura', 'González', 'laura.gonzalez@escuela.edu', 1);

-- Inserts para la tabla asignaturas
INSERT INTO asignaturas (nombre_asignatura, id_curso) VALUES
('Equipos', 1),  -- ASIX2
('Sistemas Operativos', 1),  -- ASIX2
('Lenguajes de Marcas', 2), -- EASX2
('Programación', 3), -- DAW2
('Bases de Datos', 3), -- DAW2
('Sistemas Informáticos', 4), -- DAM2
('Gestión de Proyectos', 4); -- DAM2

-- Inserts para la tabla notas
INSERT INTO notas (id_alumno, id_asignatura, nota) VALUES
(1, 1, 8.5), -- Ana en "Equipos"
(1, 2, 7.0), -- Ana en "Sistemas Operativos"
(2, 3, 6.5), -- Luis en "Lenguajes de Marcas"
(2, 4, 9.0), -- Luis en "Programación"
(3, 5, 8.0); -- María en "Bases de Datos"

-- Inserts para la tabla usuarios
INSERT INTO usuarios (nombre_usuario, apellido_usuario, username_usuario, password_usuario, tipo_usuario, DNI_usuario, correo_usuario) VALUES
('Christian', 'Monrabal', 'cmonrabal', '$2b$12$hashExampleHash1', 'Conserjeria', '12345678A', 'cmonrabal@j23.edu'),
('Juan Carlos', 'Prado', 'jcprado', '$2b$12$hashExampleHash2', 'Conserjeria', '87654321B', 'jcprado@j23.edu'),
('Daniel', 'Becerra', 'dbecerra', '$2b$12$hashExampleHash3', 'Conserjeria', '45678901C', 'dbecerra@j23.edu'),
('Sergi', 'Masip', 'smasip', '$2b$12$hashExampleHash4', 'Conserjeria', '32165498D', 'smasip@j23.edu'),
('Richard', 'Owens', 'administrador', '$2b$12$hashExampleHash5', 'Administrador', '00000000X', 'administrador@j23.edu');
