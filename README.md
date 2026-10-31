# Página personal de Jazmín — Módulo de gestión de usuarios

Sitio personal (HTML, CSS y JavaScript) con un módulo en PHP + MySQL/MariaDB para:
iniciar sesión, registrar, modificar y eliminar usuarios.

   **Sitio en línea:** https://jazmin.wuaze.com/login.php

## Archivos del módulo
| Archivo | Función |
|---|---|
| `conexion.php` | Datos de conexión a la BD y funciones comunes |
| `login.php` | Iniciar sesión (con usuario o correo) |
| `registro.php` | Registrar un usuario |
| `panel.php` | Lista de usuarios (requiere sesión) |
| `editar.php` | Modificar los datos de un usuario |
| `eliminar.php` | Eliminar un usuario |
| `logout.php` | Cerrar sesión |
| `encabezado.php`, `pie.php` | Partes comunes de las páginas PHP |
| `bd/mi_pagina.sql` | Respaldo de la base de datos |

Las contraseñas se guardan cifradas con `password_hash()` y todas las consultas usan sentencias preparadas.
La validación se hace en JavaScript (`script.js`) y se repite en PHP.

## Usuario de prueba
- Usuario: `admin`
- Contraseña: `Admin2026`
