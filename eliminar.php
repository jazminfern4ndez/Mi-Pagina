<?php
require "conexion.php";
requiereSesion();

// Solo se elimina con POST 
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: panel.php");
    exit;
}

$id = (int) ($_POST["id"] ?? 0);

if ($id == $_SESSION["id"]) {
    avisar("No puedes eliminar la cuenta con la que tienes la sesión abierta.", "alerta");
} else {
    $sql = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    if ($sql->affected_rows > 0) {
        avisar("Usuario eliminado correctamente.");
    } else {
        avisar("Ese usuario ya no existía.", "alerta");
    }
}

header("Location: panel.php");
exit;
