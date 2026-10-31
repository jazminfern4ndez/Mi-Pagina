<?php
require "conexion.php";
requiereSesion();

$id = (int) ($_GET["id"] ?? $_POST["id"] ?? 0);

$sql = $conexion->prepare("SELECT nombre, apellidos, usuario, correo FROM usuarios WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
$actual = $sql->get_result()->fetch_assoc();

if (!$actual) {
    avisar("El usuario que buscas no existe.", "alerta");
    header("Location: panel.php");
    exit;
}

$errores = [];
$d = $actual + ["clave" => "", "reclave" => ""];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($d as $campo => $v) {
        $d[$campo] = ($campo === "clave" || $campo === "reclave") ? ($_POST[$campo] ?? "") : trim($_POST[$campo] ?? "");
    }

    $errores = validarDatos($d, false);   // la contraseña es opcional al modificar
    if (!$errores && yaExiste($conexion, $d["usuario"], $d["correo"], $id)) {
        $errores[] = "Ese nombre de usuario o correo ya lo tiene otra cuenta.";
    }

    if (!$errores) {
        if ($d["clave"] !== "") {
            $hash = password_hash($d["clave"], PASSWORD_DEFAULT);
            $sql = $conexion->prepare("UPDATE usuarios SET nombre = ?, apellidos = ?, usuario = ?, correo = ?, clave = ? WHERE id = ?");
            $sql->bind_param("sssssi", $d["nombre"], $d["apellidos"], $d["usuario"], $d["correo"], $hash, $id);
        } else {
            $sql = $conexion->prepare("UPDATE usuarios SET nombre = ?, apellidos = ?, usuario = ?, correo = ? WHERE id = ?");
            $sql->bind_param("ssssi", $d["nombre"], $d["apellidos"], $d["usuario"], $d["correo"], $id);
        }
        $sql->execute();

        if ($id == $_SESSION["id"]) { $_SESSION["usuario"] = $d["usuario"]; }
        avisar("Los datos de «" . $d["usuario"] . "» se actualizaron.");
        header("Location: panel.php");
        exit;
    }
}

$titulo = "Modificar usuario";
require "encabezado.php";
?>

        <p class="etiqueta">EDITAR</p>
        <h1>Modificar usuario</h1>

        <div class="acceso">
            <?php if ($errores): ?>
                <div class="alerta"><?php foreach ($errores as $err) { echo e($err) . "<br>"; } ?></div>
            <?php endif; ?>

            <form id="form-editar" action="editar.php" method="post" onsubmit="return validarEdicion()" novalidate>
                <input type="hidden" name="id" value="<?= $id ?>">

                <fieldset>
                    <legend>Datos personales</legend>

                    <label for="nombre">Nombre: *</label><br>
                    <input type="text" id="nombre" name="nombre" value="<?= e($d["nombre"]) ?>" onblur="validarTexto('nombre')">
                    <span class="error" id="error-nombre"></span>

                    <label for="apellidos">Apellidos: *</label><br>
                    <input type="text" id="apellidos" name="apellidos" value="<?= e($d["apellidos"]) ?>" onblur="validarTexto('apellidos')">
                    <span class="error" id="error-apellidos"></span>

                    <label for="correo">Correo electrónico: *</label><br>
                    <input type="email" id="correo" name="correo" value="<?= e($d["correo"]) ?>" onblur="validarCorreo()">
                    <span class="error" id="error-correo"></span>
                </fieldset>

                <fieldset>
                    <legend>Datos de la cuenta</legend>

                    <label for="usuario">Nombre de usuario: *</label><br>
                    <input type="text" id="usuario" name="usuario" value="<?= e($d["usuario"]) ?>" onblur="validarUsuario()">
                    <span class="error" id="error-usuario"></span>

                    <p class="nota">Deja las contraseñas en blanco si no quieres cambiarla.</p>

                    <label for="clave">Nueva contraseña:</label><br>
                    <input type="password" id="clave" name="clave" onkeyup="medirClave()" onblur="validarClave(false)">
                    <span id="fuerza"></span>
                    <span class="error" id="error-clave"></span>

                    <label for="reclave">Repite la nueva contraseña:</label><br>
                    <input type="password" id="reclave" name="reclave" onkeyup="validarReclave()" onblur="validarReclave()">
                    <span class="error" id="error-reclave"></span>

                    <label><input type="checkbox" onclick="verClaves(this)"> Mostrar contraseñas</label>
                </fieldset>

                <input type="submit" value="Guardar cambios">
                <a class="boton secundario" href="panel.php">Cancelar</a>
            </form>
        </div>

<?php require "pie.php"; ?>
