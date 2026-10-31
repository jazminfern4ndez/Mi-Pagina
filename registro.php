<?php
require "conexion.php";

$errores = [];
$d = ["nombre" => "", "apellidos" => "", "usuario" => "", "correo" => "", "clave" => "", "reclave" => ""];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($d as $campo => $v) {
        $d[$campo] = ($campo === "clave" || $campo === "reclave") ? ($_POST[$campo] ?? "") : trim($_POST[$campo] ?? "");
    }

    $errores = validarDatos($d, true);
    if (!$errores && yaExiste($conexion, $d["usuario"], $d["correo"])) {
        $errores[] = "Ese nombre de usuario o correo ya está registrado.";
    }

    if (!$errores) {
        $hash = password_hash($d["clave"], PASSWORD_DEFAULT);   // nunca se guarda la contraseña en texto plano
        $sql = $conexion->prepare("INSERT INTO usuarios (nombre, apellidos, usuario, correo, clave) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("sssss", $d["nombre"], $d["apellidos"], $d["usuario"], $d["correo"], $hash);
        $sql->execute();

        avisar("Usuario «" . $d["usuario"] . "» registrado correctamente.");
        header("Location: " . (isset($_SESSION["id"]) ? "panel.php" : "login.php"));
        exit;
    }
}

$titulo = "Registrar usuario";
require "encabezado.php";
?>

        <p class="etiqueta">ÚNETE</p>
        <h1>Registrar usuario</h1>

        <div class="acceso">
            <p>Todos los campos marcados con * son obligatorios.</p>

            <?php if ($errores): ?>
                <div class="alerta"><?php foreach ($errores as $err) { echo e($err) . "<br>"; } ?></div>
            <?php endif; ?>

            <form id="form-registro" action="registro.php" method="post" onsubmit="return validarRegistro()" novalidate>
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

                    <label for="clave">Contraseña: *</label><br>
                    <input type="password" id="clave" name="clave" onkeyup="medirClave()" onblur="validarClave(true)">
                    <span id="fuerza"></span>
                    <span class="error" id="error-clave"></span>

                    <label for="reclave">Repite la contraseña: *</label><br>
                    <input type="password" id="reclave" name="reclave" onkeyup="validarReclave()" onblur="validarReclave()">
                    <span class="error" id="error-reclave"></span>

                    <label><input type="checkbox" onclick="verClaves(this)"> Mostrar contraseñas</label>
                </fieldset>

                <input type="submit" value="Registrarme">
                <button type="reset" class="secundario" onclick="limpiarErrores()">Limpiar</button>
            </form>

            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>.</p>
        </div>

<?php require "pie.php"; ?>
