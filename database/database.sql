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
    estado ENUM('libre','ocupada', 'reservada') NOT NULL,
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
