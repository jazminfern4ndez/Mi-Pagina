-- =====================================================
--  Respaldo de la base de datos del módulo de usuarios
--  Página personal de Jazmín E. Och Fernández
--  Compatible con MySQL / MariaDB (XAMPP)
-- =====================================================

-- En XAMPP: crea primero la base "mi_pagina" en phpMyAdmin
-- (o descomenta las dos líneas siguientes) y luego importa este archivo.
-- En el hosting NO uses estas líneas: el hosting crea la base por ti.

-- CREATE DATABASE IF NOT EXISTS mi_pagina CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE mi_pagina;

SET NAMES utf8mb4;

DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre      VARCHAR(60)  NOT NULL,
    apellidos   VARCHAR(80)  NOT NULL,
    usuario     VARCHAR(20)  NOT NULL,
    correo      VARCHAR(120) NOT NULL,
    clave       VARCHAR(255) NOT NULL,   -- se guarda cifrada con password_hash()
    creado      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_usuario (usuario),
    UNIQUE KEY uk_correo (correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuario de prueba  ->  usuario: admin   contraseña: Admin2026
INSERT INTO usuarios (nombre, apellidos, usuario, correo, clave) VALUES
('Jazmín', 'Och Fernández', 'admin', 'fernandezjazmin075@gmail.com',
 '$2y$10$3wH9CwLkHBOPlEGyDVAPMexDx49GSKM2Inx5AzVlRfOHEpHk74I0G');
