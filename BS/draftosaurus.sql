CREATE DATABASE draftosaurus
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;
USE draftosaurus;

CREATE TABLE usuario (
  id_usuario      INT AUTO_INCREMENT PRIMARY KEY,
  nombre_usuario  VARCHAR(50) NOT NULL,
  contrasena      VARCHAR(255) NOT NULL,
  CONSTRAINT uq_usuario_nombre UNIQUE (nombre_usuario)
);

CREATE TABLE dinosaurio (
  id_dinosaurio INT AUTO_INCREMENT PRIMARY KEY,
  especie       VARCHAR(50) NOT NULL
);

CREATE TABLE partida (
  id_partida  INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id  INT NOT NULL,
  estado      ENUM('en_curso','finalizada') NOT NULL DEFAULT 'en_curso',
  fecha_hora  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_partida_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuario(id_usuario)
    ON DELETE CASCADE
);

CREATE TABLE tablero (
  id_tablero  INT AUTO_INCREMENT PRIMARY KEY,
  partida_id  INT NOT NULL,
  usuario_id  INT NOT NULL,
  puntaje     INT NOT NULL DEFAULT 0,
  CONSTRAINT uq_tablero_partida_usuario UNIQUE (partida_id, usuario_id),
  CONSTRAINT fk_tablero_partida
    FOREIGN KEY (partida_id) REFERENCES partida(id_partida)
    ON DELETE CASCADE,
  CONSTRAINT fk_tablero_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuario(id_usuario)
    ON DELETE CASCADE
);

CREATE TABLE contiene (
  id_tablero     INT NOT NULL,
  slot_id        VARCHAR(64) NOT NULL,
  id_dinosaurio  INT NOT NULL,
  PRIMARY KEY (id_tablero, slot_id),
  KEY idx_contiene_dino (id_dinosaurio),
  CONSTRAINT fk_contiene_tablero
    FOREIGN KEY (id_tablero) REFERENCES tablero(id_tablero)
    ON DELETE CASCADE,
  CONSTRAINT fk_contiene_dino
    FOREIGN KEY (id_dinosaurio) REFERENCES dinosaurio(id_dinosaurio)
);

CREATE INDEX idx_partida_usuario_estado ON partida (usuario_id, estado);
CREATE INDEX idx_tablero_usuario       ON tablero (usuario_id);

INSERT INTO usuario (id_usuario, nombre_usuario, contrasena) VALUES
(1, 'ana',  '$2y$10$W9t3x4x4mK3UoK2m4s7fHuH8QmB2i6mT/5o7mKPh7s6iZVq4oPh1S'),
(2, 'juan', '$2y$10$W9t3x4x4mK3UoK2m4s7fHuH8QmB2i6mT/5o7mKPh7s6iZVq4oPh1S');

INSERT INTO dinosaurio (id_dinosaurio, especie) VALUES
(1,'T-Rex'),(2,'Stegosaurus'),(3,'Triceratops'),
(4,'Brachiosaurus'),(5,'Velociraptor'),(6,'Parasaurolophus');

INSERT INTO partida (id_partida, usuario_id, estado, fecha_hora) VALUES
(10001, 1, 'en_curso', NOW());

INSERT INTO tablero (id_tablero, partida_id, usuario_id, puntaje) VALUES
(20001, 10001, 1, 0);

ALTER TABLE usuario
  ADD rol ENUM('usuario','admin') NOT NULL DEFAULT 'usuario' AFTER contrasena;
