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

-- Inserts para conserjería
INSERT INTO usuarios (nombre_usuario, apellido_usuario, username_usuario, password_usuario, tipo_usuario, DNI_usuario, correo_usuario) VALUES
('Christian', 'Monrabal', 'cmonrabal', '$2b$12$gvjVuQbOv7d7Ya5VnLcbyu2EUeVxXlQppLZBtly9l3/1dsQm1ZTki', 'Conserjeria', '12345678A', 'cmonrabal@j23.edu'),
('Juan Carlos', 'Prado', 'jcprado', '$2b$12$gvjVuQbOv7d7Ya5VnLcbyu2EUeVxXlQppLZBtly9l3/1dsQm1ZTki', 'Conserjeria', '87654321B', 'jcprado@j23.edu'),
('Daniel', 'Becerra', 'dbecerra', '$2b$12$gvjVuQbOv7d7Ya5VnLcbyu2EUeVxXlQppLZBtly9l3/1dsQm1ZTki', 'Conserjeria', '45678901C', 'dbecerra@j23.edu'),
('Sergi', 'Masip', 'smasip', '$2b$12$gvjVuQbOv7d7Ya5VnLcbyu2EUeVxXlQppLZBtly9l3/1dsQm1ZTki', 'Conserjeria', '32165498D', 'smasip@j23.edu'),
('Richard', 'Owens', 'administrador', '$2b$12$gvjVuQbOv7d7Ya5VnLcbyu2EUeVxXlQppLZBtly9l3/1dsQm1ZTki', 'Administrador', '00000000X', 'administrador@j23.edu');

select * from usuarios;