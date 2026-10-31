<?php  ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Jazmín E. Och Fernández">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Dancing+Script:wght@600&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="estilo.css">
    <script type="text/javascript" src="script.js" defer></script>
</head>

<body>

    <header class="cabecera">
        <p class="marca">Jaz ✦</p>
        <nav>
            <ul>
                <li><a href="index.html">Inicio</a></li>
                <li><a href="autobiografia.html">Autobiografía</a></li>
                <li><a href="galery.html">Galería</a></li>
                <li><a href="contacto.html">Contacto</a></li>
                <li><a href="login.php" class="activa">Usuarios</a></li>
            </ul>
        </nav>
        <?php if (isset($_SESSION["id"])): ?>
            <p class="sesion">Sesión de <strong><?= e($_SESSION["usuario"]) ?></strong> ·
                <a href="panel.php">Panel</a> · <a href="logout.php">Cerrar sesión</a></p>
        <?php endif; ?>
    </header>

    <main class="contenedor">
