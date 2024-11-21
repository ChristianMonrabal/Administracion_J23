CREATE DATABASE AD_J23;
USE AD_J23;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    apellido_usuario VARCHAR(50) NOT NULL,
    username_usuario VARCHAR(20) NOT NULL UNIQUE,
    password_usuario CHAR(64) NOT NULL,
    tipo_usuario ENUM('Alumno', 'Administrador') NOT NULL default 'Alumno',
    DNI_usuario CHAR(9) NOT NULL,
    correo_usuario VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE alumnos (
    id_alumno INT AUTO_INCREMENT PRIMARY KEY,
    nombre_alumno VARCHAR(50) NOT NULL,
    apellido_alumno VARCHAR(50) NOT NULL,
    matricula_alumno VARCHAR(20) NOT NULL UNIQUE,
    correo_alumno VARCHAR(50) NOT NULL UNIQUE,
    id_curso INT NOT NULL,
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso)
);

CREATE TABLE cursos (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    nombre_curso VARCHAR(50) NOT NULL
);

CREATE TABLE asignaturas (
    id_asignatura INT AUTO_INCREMENT PRIMARY KEY,
    nombre_asignatura VARCHAR(50) NOT NULL,
    id_curso INT NOT NULL,
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso)
);

CREATE TABLE notas (
    id_nota INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    asignatura VARCHAR(50) NOT NULL,
    nota DECIMAL(4, 2) NOT NULL,
    FOREIGN KEY (id_alumno) REFERENCES usuarios(id_usuario)
);

INSERT INTO cursos (nombre_curso) VALUES
('ASIX2'),
('EASX2'),
('DAW2'),
('DAM2');

INSERT INTO alumnos (nombre_alumno, apellido_alumno, matricula_alumno, correo_alumno, id_curso) VALUES
('Ana', 'García', 'A001', 'ana.garcia@escuela.edu', 1),
('Luis', 'Martínez', 'A002', 'luis.martinez@escuela.edu', 2),
('María', 'López', 'A003', 'maria.lopez@escuela.edu', 3),
('Carlos', 'Hernández', 'A004', 'carlos.hernandez@escuela.edu', 4),
('Laura', 'González', 'A005', 'laura.gonzalez@escuela.edu', 1);

-- Inserts de ejemplo para las notas
INSERT INTO notas (id_alumno, asignatura, nota) VALUES
(1, 'Matemáticas', 8.5),
(1, 'Ciencias', 7.0),
(2, 'Matemáticas', 6.5),
(2, 'Historia', 9.0),
(3, 'Literatura', 8.0);

-- Inserts para conserjería
INSERT INTO usuarios (nombre_usuario, apellido_usuario, username_usuario, password_usuario, tipo_usuario, DNI_usuario, correo_usuario) VALUES
('Christian', 'Monrabal', 'cmonrabal', '$2b$12$gvjVuQbOv7d7Ya5VnLcbyu2EUeVxXlQppLZBtly9l3/1dsQm1ZTki', 'Conserjeria', '12345678A', 'cmonrabal@j23.edu'),
('Juan Carlos', 'Prado', 'jcprado', '$2b$12$gvjVuQbOv7d7Ya5VnLcbyu2EUeVxXlQppLZBtly9l3/1dsQm1ZTki', 'Conserjeria', '87654321B', 'jcprado@j23.edu'),
('Daniel', 'Becerra', 'dbecerra', '$2b$12$gvjVuQbOv7d7Ya5VnLcbyu2EUeVxXlQppLZBtly9l3/1dsQm1ZTki', 'Conserjeria', '45678901C', 'dbecerra@j23.edu'),
('Sergi', 'Masip', 'smasip', '$2b$12$gvjVuQbOv7d7Ya5VnLcbyu2EUeVxXlQppLZBtly9l3/1dsQm1ZTki', 'Conserjeria', '32165498D', 'smasip@j23.edu'),
('Richard', 'Owens', 'administrador', '$2b$12$gvjVuQbOv7d7Ya5VnLcbyu2EUeVxXlQppLZBtly9l3/1dsQm1ZTki', 'Administrador', '00000000X', 'administrador@j23.edu');
