<?php
require "conexion.php";

// Si ya hay sesión, directo al panel
if (isset($_SESSION["id"])) {
    header("Location: panel.php");
    exit;
}

$error = "";
$usuario = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"] ?? "");
    $clave   = $_POST["clave"] ?? "";

    // Se puede entrar con el nombre de usuario o con el correo
    $sql = $conexion->prepare("SELECT id, usuario, nombre, clave FROM usuarios WHERE usuario = ? OR correo = ?");
    $sql->bind_param("ss", $usuario, $usuario);
    $sql->execute();
    $fila = $sql->get_result()->fetch_assoc();

    if ($fila && password_verify($clave, $fila["clave"])) {
        session_regenerate_id(true);
        $_SESSION["id"]      = $fila["id"];
        $_SESSION["usuario"] = $fila["usuario"];
        avisar("¡Hola de nuevo, " . $fila["nombre"] . "! ♡");
        header("Location: panel.php");
        exit;
    }
    $error = "Usuario o contraseña incorrectos.";
}

$titulo = "Iniciar sesión";
require "encabezado.php";
?>

        <p class="etiqueta">BIENVENIDO DE VUELTA</p>
        <h1>Iniciar sesión</h1>

        <div class="acceso">
            <?php mostrarAviso(); ?>
            <?php if ($error !== ""): ?><div class="alerta"><?= e($error) ?></div><?php endif; ?>

            <form id="form-login" action="login.php" method="post" onsubmit="return validarLogin()" novalidate>
                <fieldset>
                    <legend>Tus datos de acceso</legend>

                    <label for="usuario">Usuario o correo: *</label><br>
                    <input type="text" id="usuario" name="usuario" value="<?= e($usuario) ?>" onblur="validarVacio('usuario')">
                    <span class="error" id="error-usuario"></span>

                    <label for="clave">Contraseña: *</label><br>
                    <input type="password" id="clave" name="clave" onblur="validarVacio('clave')">
                    <span class="error" id="error-clave"></span>

                    <label><input type="checkbox" onclick="verClaves(this)"> Mostrar contraseña</label>
                </fieldset>

                <input type="submit" value="Entrar">
            </form>

            <p>¿Aún no tienes cuenta? <a href="registro.php">Regístrate aquí</a>.</p>
        </div>

<?php require "pie.php"; ?>
