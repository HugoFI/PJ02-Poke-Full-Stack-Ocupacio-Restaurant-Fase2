DROP DATABASE IF EXISTS bd_pokefullStack_fase2;
CREATE DATABASE bd_pokefullStack_fase2;

USE bd_pokefullStack_fase2;

CREATE TABLE usuarios(
    idUsuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(60) NOT NULL,
    apellidos VARCHAR(80) NOT NULL,
    nombreUsu VARCHAR(60) NOT NULL,
    dni VARCHAR(10) NOT NULL,
    telefono VARCHAR(10) NOT NULL,
    email VARCHAR(60) NOT NULL,
    fechaContratacion DATE NOT NULL,
    rol ENUM('admin','gerente', 'mantenimiento','camarero') NOT NULL,
    estado ENUM('activo','baja') NOT NULL DEFAULT 'activo',
    password VARCHAR(60) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE sala(
    idSala INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(60) NOT NULL,
    tipo ENUM('comedor','terraza','sala privada') NOT NULL
) ENGINE=InnoDB;

CREATE TABLE mesa(
    idMesa INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(60) NOT NULL,
    numSillas INT NOT NULL,
    estado ENUM('libre','ocupada', 'reservada', 'mantenimiento') NOT NULL,
    idSala INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE historico(
    idHistorico INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    idMesa INT NOT NULL,
    idSala INT NOT NULL,
    idUsuario INT NOT NULL,
    horaOcupacion DATETIME NOT NULL,
    horaDesocupacion DATETIME DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE reservas(
    idReserva INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    idMesa INT NOT NULL,
    idSala INT NOT NULL,
    idUsuario INT NOT NULL,
    nombreCliente VARCHAR(60) NOT NULL,
    telefonoCliente VARCHAR(10) NOT NULL,
    fechaReserva DATETIME NOT NULL,
    horaInicio DATETIME NOT NULL,
    horaFin DATETIME NOT NULL,
    numPersonas INT NOT NULL
) ENGINE=InnoDB;

alter table historico
add constraint fk_mesa_historico
foreign key (idMesa) references mesa(idMesa);

alter table historico
add constraint fk_usuario_historico
foreign key (idUsuario) references usuarios(idUsuario);

alter table historico
add constraint fk_sala_historico
foreign key (idSala) references sala(idSala);

alter table mesa
add constraint fk_sala_mesa 
foreign key (idSala) references sala(idSala);


ALTER TABLE reservas
ADD CONSTRAINT fk_reserva_mesa
FOREIGN KEY (idMesa) REFERENCES mesa(idMesa);

ALTER TABLE reservas
ADD CONSTRAINT fk_reserva_sala
FOREIGN KEY (idSala) REFERENCES sala(idSala);

ALTER TABLE reservas
ADD CONSTRAINT fk_reserva_usuario
FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario);


insert into sala (nombre, tipo) values("sinnoh", "comedor");
insert into sala (nombre, tipo) values("unova", "comedor");
insert into sala (nombre, tipo) values("kanto", "terraza");
insert into sala (nombre, tipo) values("jotho", "terraza");
insert into sala (nombre, tipo) values("hoenn", "terraza");
insert into sala (nombre, tipo) values("alola", "sala privada");
insert into sala (nombre, tipo) values("galar", "sala privada");
insert into sala (nombre, tipo) values("kalos", "sala privada");
insert into sala (nombre, tipo) values("paldea", "sala privada");

-- Kanto
insert into mesa (nombre, numSillas, estado, idSala) values("PLATEADA", 2, "libre", 1);
insert into mesa (nombre, numSillas, estado, idSala) values("CELESTE", 2, "libre", 1);
insert into mesa (nombre, numSillas, estado, idSala) values("CARMÍN", 3, "libre", 1);
insert into mesa (nombre, numSillas, estado, idSala) values("AZULONA", 4, "libre", 1);
insert into mesa (nombre, numSillas, estado, idSala) values("FUCSIA", 4, "libre", 1);
insert into mesa (nombre, numSillas, estado, idSala) values("AZAFRÁN", 3, "libre", 1);
insert into mesa (nombre, numSillas, estado, idSala) values("CANELA", 3, "libre", 1);
insert into mesa (nombre, numSillas, estado, idSala) values("VERDE", 4, "libre", 1);

-- Johto
insert into mesa (nombre, numSillas, estado, idSala) values("VIOLETA", 2, "libre", 2);
insert into mesa (nombre, numSillas, estado, idSala) values("PRIMAVERA", 3, "libre", 2);
insert into mesa (nombre, numSillas, estado, idSala) values("TRIGAL", 4, "libre", 2);
insert into mesa (nombre, numSillas, estado, idSala) values("MALVA", 3, "libre", 2);
insert into mesa (nombre, numSillas, estado, idSala) values("IRIS", 2, "libre", 2);
insert into mesa (nombre, numSillas, estado, idSala) values("OLIVO", 2, "libre", 2);
insert into mesa (nombre, numSillas, estado, idSala) values("ORQUÍDEA", 3, "libre", 2);
insert into mesa (nombre, numSillas, estado, idSala) values("ENDRINO", 4, "libre", 2);

-- Hoenn
insert into mesa (nombre, numSillas, estado, idSala) values("FÉRRICA", 2, "libre", 3);
insert into mesa (nombre, numSillas, estado, idSala) values("AZULIZA", 3, "libre", 3);
insert into mesa (nombre, numSillas, estado, idSala) values("MALVALONA", 3, "libre", 3);
insert into mesa (nombre, numSillas, estado, idSala) values("LAVACALDA", 4, "libre", 3);
insert into mesa (nombre, numSillas, estado, idSala) values("PETALIA", 3, "libre", 3);
insert into mesa (nombre, numSillas, estado, idSala) values("ARBOLEDA", 4, "libre", 3);
insert into mesa (nombre, numSillas, estado, idSala) values("CALIGULA", 4, "libre", 3);
insert into mesa (nombre, numSillas, estado, idSala) values("ALGARIA", 3, "libre", 3);

-- Sinnoh
insert into mesa (nombre, numSillas, estado, idSala) values("ROCAVELO", 3, "libre", 4);
insert into mesa (nombre, numSillas, estado, idSala) values("VETUSTA", 3, "libre", 4);
insert into mesa (nombre, numSillas, estado, idSala) values("MARINA", 4, "libre", 4);
insert into mesa (nombre, numSillas, estado, idSala) values("CORAZÓN", 3, "libre", 4);
insert into mesa (nombre, numSillas, estado, idSala) values("PUNTANEVA", 4, "libre", 4);
insert into mesa (nombre, numSillas, estado, idSala) values("MINERA", 3, "libre", 4);
insert into mesa (nombre, numSillas, estado, idSala) values("SOLAR", 4, "libre", 4);
insert into mesa (nombre, numSillas, estado, idSala) values("ALBA", 4, "libre", 4);

-- Unova
insert into mesa (nombre, numSillas, estado, idSala) values("GRES", 2, "libre", 5);
insert into mesa (nombre, numSillas, estado, idSala) values("ESMALTE", 3, "libre", 5);
insert into mesa (nombre, numSillas, estado, idSala) values("PORCELANA", 4, "libre", 5);
insert into mesa (nombre, numSillas, estado, idSala) values("MAYÓLICA", 4, "libre", 5);
insert into mesa (nombre, numSillas, estado, idSala) values("FAYENZA", 5, "libre", 5);
insert into mesa (nombre, numSillas, estado, idSala) values("LOZA", 5, "libre", 5);
insert into mesa (nombre, numSillas, estado, idSala) values("TEJA", 6, "libre", 5);
insert into mesa (nombre, numSillas, estado, idSala) values("CAOLÍN", 8, "libre", 5);

-- Kalos
insert into mesa (nombre, numSillas, estado, idSala) values("NOVARTE", 2, "libre", 6);
insert into mesa (nombre, numSillas, estado, idSala) values("RELIEVE", 3, "libre", 6);
insert into mesa (nombre, numSillas, estado, idSala) values("LUMINALIA", 5, "libre", 6);
insert into mesa (nombre, numSillas, estado, idSala) values("YANTRA", 6, "libre", 6);
insert into mesa (nombre, numSillas, estado, idSala) values("ROMANTIS", 3, "libre", 6);
insert into mesa (nombre, numSillas, estado, idSala) values("TEMPERA", 4, "libre", 6);
insert into mesa (nombre, numSillas, estado, idSala) values("FLUXUS", 4, "libre", 6);
insert into mesa (nombre, numSillas, estado, idSala) values("FRACTAL", 4, "libre", 6);

-- Alola
insert into mesa (nombre, numSillas, estado, idSala) values("MELEMELE-A", 2, "libre", 7);
insert into mesa (nombre, numSillas, estado, idSala) values("MELEMELE-B", 4, "libre", 7);
insert into mesa (nombre, numSillas, estado, idSala) values("AKALA-A", 2, "libre", 7);
insert into mesa (nombre, numSillas, estado, idSala) values("AKALA-B", 4, "libre", 7);
insert into mesa (nombre, numSillas, estado, idSala) values("ULA-ULA-A", 2, "libre", 7);
insert into mesa (nombre, numSillas, estado, idSala) values("ULA-ULA-B", 4, "libre", 7);
insert into mesa (nombre, numSillas, estado, idSala) values("PONI-A", 2, "libre", 7);
insert into mesa (nombre, numSillas, estado, idSala) values("PONI-B", 4, "libre", 7);

-- Galar
insert into mesa (nombre, numSillas, estado, idSala) values("PISTON-A", 2, "libre", 8);
insert into mesa (nombre, numSillas, estado, idSala) values("PISTON-B", 4, "libre", 8);
insert into mesa (nombre, numSillas, estado, idSala) values("ARTEJO-A", 2, "libre", 8);
insert into mesa (nombre, numSillas, estado, idSala) values("ARTEJO-B", 4, "libre", 8);
insert into mesa (nombre, numSillas, estado, idSala) values("LADERA-A", 2, "libre", 8);
insert into mesa (nombre, numSillas, estado, idSala) values("LADERA-B", 4, "libre", 8);
insert into mesa (nombre, numSillas, estado, idSala) values("AURIGA", 3, "libre", 8);
insert into mesa (nombre, numSillas, estado, idSala) values("PUNTERA", 4, "libre", 8);

-- Paldea
insert into mesa (nombre, numSillas, estado, idSala) values("LEUDAL", 2, "libre", 9);
insert into mesa (nombre, numSillas, estado, idSala) values("MEZCLORA", 2, "libre", 9);
insert into mesa (nombre, numSillas, estado, idSala) values("ALFORNO", 3, "libre", 9);
insert into mesa (nombre, numSillas, estado, idSala) values("CANTARA", 2, "libre", 9);
insert into mesa (nombre, numSillas, estado, idSala) values("FRAGUA", 3, "libre", 9);
insert into mesa (nombre, numSillas, estado, idSala) values("NAPADA", 4, "libre", 9);
insert into mesa (nombre, numSillas, estado, idSala) values("MEDALI", 4, "libre", 9);
insert into mesa (nombre, numSillas, estado, idSala) values("ALMIZCLE", 2, "libre", 9);


-- INSERCION DE USUARIOS
INSERT INTO usuarios (nombre, apellidos, nombreUsu, dni, telefono, email, fechaContratacion, rol, estado, password)
VALUES
('admin', 'Admin Apellido', 'admin', '00000001A', '600000001', 'admin@poke.com', '2024-01-01', 'admin', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu'),
('gerente', 'Gerente Apellido', 'gerente', '00000002B', '600000002', 'gerente@poke.com', '2024-01-02', 'gerente', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu'),
('mantenimiento', 'Mantenimiento Apellido', 'mantenimiento', '00000003C', '600000003', 'mantenimiento@poke.com', '2024-01-03', 'mantenimiento', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu'),
('camarero', 'Camarero Apellido', 'camarero', '00000004D', '600000004', 'camarero@poke.com', '2024-01-04', 'camarero', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu');

-- 2 usuarios más de cada rol
-- Admin
INSERT INTO usuarios (nombre, apellidos, nombreUsu, dni, telefono, email, fechaContratacion, rol, estado, password)
VALUES
('Ana', 'Administra', 'anaadmin', '00000005E', '600000005', 'ana.admin@poke.com', '2024-02-01', 'admin', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu'),
('Pedro', 'Administrador', 'pedroadmin', '00000006F', '600000006', 'pedro.admin@poke.com', '2024-02-02', 'admin', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu');

-- Gerente
INSERT INTO usuarios (nombre, apellidos, nombreUsu, dni, telefono, email, fechaContratacion, rol, estado, password)
VALUES
('Laura', 'Gerencia', 'lauragerente', '00000007G', '600000007', 'laura.gerente@poke.com', '2024-03-01', 'gerente', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu'),
('Carlos', 'Gerentez', 'carlosgerente', '00000008H', '600000008', 'carlos.gerente@poke.com', '2024-03-02', 'gerente', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu');

-- Mantenimiento
INSERT INTO usuarios (nombre, apellidos, nombreUsu, dni, telefono, email, fechaContratacion, rol, estado, password)
VALUES
('Mario', 'Mantenido', 'mariomant', '00000009I', '600000009', 'mario.mant@poke.com', '2024-04-01', 'mantenimiento', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu'),
('Sara', 'Mantenedora', 'saramant', '00000010J', '600000010', 'sara.mant@poke.com', '2024-04-02', 'mantenimiento', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu');

-- Camarero
INSERT INTO usuarios (nombre, apellidos, nombreUsu, dni, telefono, email, fechaContratacion, rol, estado, password)
VALUES
('Lucía', 'Camarera', 'luciacama', '00000011K', '600000011', 'lucia.cama@poke.com', '2024-05-01', 'camarero', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu'),
('David', 'Camareroz', 'davidcama', '00000012L', '600000012', 'david.cama@poke.com', '2024-05-02', 'camarero', 'activo', '$2y$10$3A.7Kt7W7ANBM5WMli5O/e1taIwMQaGLM3MKYWMbMC0CzoKmGr5iu');
