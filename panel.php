<?php
require "conexion.php";
requiereSesion();

$resultado = $conexion->query("SELECT id, nombre, apellidos, usuario, correo, creado FROM usuarios ORDER BY id");

$titulo = "Gestión de usuarios";
require "encabezado.php";
?>

        <p class="etiqueta">PANEL</p>
        <h1>Gestión de usuarios</h1>

        <?php mostrarAviso(); ?>

        <p>
            Aquí puedes ver a todos los usuarios registrados, modificar sus datos o eliminarlos.
            Hay <strong><?= $resultado->num_rows ?></strong> usuario(s) en la base de datos.
        </p>

        <p><a class="boton" href="registro.php">+ Registrar nuevo usuario</a></p>

        <div class="tabla-desliza">
            <table class="usuarios">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
                <?php while ($u = $resultado->fetch_assoc()): ?>
                <tr<?= $u["id"] == $_SESSION["id"] ? ' class="yo"' : '' ?>>
                    <td><?= $u["id"] ?></td>
                    <td><?= e($u["nombre"] . " " . $u["apellidos"]) ?></td>
                    <td><?= e($u["usuario"]) ?><?= $u["id"] == $_SESSION["id"] ? " (tú)" : "" ?></td>
                    <td><?= e($u["correo"]) ?></td>
                    <td><?= date("d/m/Y", strtotime($u["creado"])) ?></td>
                    <td class="acciones">
                        <a class="boton chico" href="editar.php?id=<?= $u["id"] ?>">Modificar</a>
                        <?php if ($u["id"] != $_SESSION["id"]): ?>
                        <form action="eliminar.php" method="post"
                              onsubmit="return confirmarEliminar('<?= e($u["usuario"]) ?>')">
                            <input type="hidden" name="id" value="<?= $u["id"] ?>">
                            <button type="submit" class="chico peligro">Eliminar</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <p class="nota">Por seguridad no puedes eliminar la cuenta con la que tienes la sesión abierta.</p>

<?php require "pie.php"; ?>
