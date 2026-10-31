<?php
session_start();
$_SESSION = [];
session_destroy();

session_start();   // sesión nueva y vacía solo para mostrar el aviso
$_SESSION["aviso"] = ["texto" => "Cerraste sesión. ¡Vuelve pronto! ✦", "tipo" => "exito"];
header("Location: login.php");
exit;
