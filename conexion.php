<?php

$servidor   = "localhost";
$usuarioBD  = "root";
$claveBD    = "";
$nombreBD   = "mi_pagina";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = new mysqli($servidor, $usuarioBD, $claveBD, $nombreBD);
    $conexion->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("<p style='font-family:sans-serif;color:#6d1f2f'>No se pudo conectar a la base de datos. " .
        "Revisa que MySQL esté encendido en XAMPP y que los datos de conexion.php sean correctos.</p>");
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function e($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, "UTF-8");
}


function avisar($texto, $tipo = "exito") {
    $_SESSION["aviso"] = ["texto" => $texto, "tipo" => $tipo];
}

function mostrarAviso() {
    if (isset($_SESSION["aviso"])) {
        $a = $_SESSION["aviso"];
        echo "<div class='" . e($a["tipo"]) . "'>" . e($a["texto"]) . "</div>";
        unset($_SESSION["aviso"]);
    }
}

function requiereSesion() {
    if (!isset($_SESSION["id"])) {
        avisar("Inicia sesión para entrar a la gestión de usuarios.", "alerta");
        header("Location: login.php");
        exit;
    }
}
function validarDatos($d, $claveObligatoria) {
    $errores = [];
    $letras = "/^[A-Za-zÁÉÍÓÚÜáéíóúüÑñ ]{2,}$/u";
    if (!preg_match($letras, $d["nombre"]))    { $errores[] = "El nombre solo admite letras (mínimo 2)."; }
    if (!preg_match($letras, $d["apellidos"])) { $errores[] = "Los apellidos solo admiten letras (mínimo 2)."; }
    if (!preg_match("/^[A-Za-z0-9_]{4,20}$/", $d["usuario"])) {
        $errores[] = "El usuario debe tener de 4 a 20 letras, números o guion bajo.";
    }
    if (!filter_var($d["correo"], FILTER_VALIDATE_EMAIL)) { $errores[] = "El correo no es válido."; }

    if ($claveObligatoria || $d["clave"] !== "") {
        if (strlen($d["clave"]) < 8 || !preg_match("/[A-Za-z]/", $d["clave"]) || !preg_match("/[0-9]/", $d["clave"])) {
            $errores[] = "La contraseña debe tener al menos 8 caracteres, con letras y números.";
        }
        if ($d["clave"] !== $d["reclave"]) { $errores[] = "Las contraseñas no coinciden."; }
    }
    return $errores;
}

// existe ese usuario o correo
function yaExiste($conexion, $usuario, $correo, $excepto = 0) {
    $sql = $conexion->prepare("SELECT id FROM usuarios WHERE (usuario = ? OR correo = ?) AND id <> ?");
    $sql->bind_param("ssi", $usuario, $correo, $excepto);
    $sql->execute();
    return $sql->get_result()->num_rows > 0;
}
