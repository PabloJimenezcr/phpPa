<?php

// se inicia la sesion para gestionar el token de seguridad
session_start();

// se carga la conexion con la base de datos
require_once __DIR__ . "/config/conexion.php";

// se crea el token csrf si todavia no existe
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// se inicializan las variables del formulario
$nombre = "";
$correo = "";
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // se reciben los datos como texto y se eliminan espacios externos
    $nombre = is_string($_POST["nombre"] ?? null)
        ? trim($_POST["nombre"])
        : "";

    $correo = is_string($_POST["correo"] ?? null)
        ? trim($_POST["correo"])
        : "";

    $contrasena = is_string($_POST["contrasena"] ?? null)
        ? $_POST["contrasena"]
        : "";

    $token = is_string($_POST["csrf_token"] ?? null)
        ? $_POST["csrf_token"]
        : "";

    // se comprueba que el token recibido coincida con el de la sesion
    if (
        !isset($_SESSION["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $token)
    ) {
        $mensaje = "La solicitud no es valida";
    } elseif (
        $nombre === "" ||
        strlen($nombre) > 100 ||
        !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
        strlen($correo) > 150 ||
        strlen($contrasena) < 8 ||
        strlen($contrasena) > 72
    ) {
        // se validan los datos antes de guardarlos
        $mensaje = "Revise los datos y utilice una contraseña de al menos 8 caracteres";
    } else {
        try {
            // se consulta si el correo ya esta registrado
            $consulta = $conexion->prepare(
                "SELECT id FROM usuarios WHERE correo = :correo"
            );

            $consulta->execute([
                "correo" => $correo
            ]);

            if ($consulta->fetch()) {
                $mensaje = "No fue posible registrar el correo indicado";
            } else {
                // se genera un hash seguro de la contraseña
                $hash = password_hash(
                    $contrasena,
                    PASSWORD_DEFAULT
                );

                // se prepara la insercion del nuevo usuario
                $consulta = $conexion->prepare(
                    "INSERT INTO usuarios (nombre, correo, password_hash)
                     VALUES (:nombre, :correo, :password_hash)"
                );

                // se guardan los datos mediante parametros
                $consulta->execute([
                    "nombre" => $nombre,
                    "correo" => $correo,
                    "password_hash" => $hash
                ]);

                // se muestra una confirmacion sin revelar informacion sensible
                $mensaje = "Registro completado correctamente";
            }
        } catch (PDOException $error) {
            // se registra el error sin mostrar detalles internos
            error_log($error->getMessage());

            // se muestra un mensaje general
            $mensaje = "No fue posible completar el registro";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro seguro</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <main class="contenedor">
        <h1>Crear cuenta</h1>

        <?php if ($mensaje !== ""): ?>
            <p class="mensaje">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="registro.php">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">

            <label for="nombre">Nombre</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                maxlength="100"
                required
                value="<?= htmlspecialchars($nombre, ENT_QUOTES, "UTF-8") ?>">

            <label for="correo">Correo electrónico</label>
            <input
                type="email"
                id="correo"
                name="correo"
                maxlength="150"
                required
                value="<?= htmlspecialchars($correo, ENT_QUOTES, "UTF-8") ?>">

            <label for="contrasena">Contraseña</label>
            <input
                type="password"
                id="contrasena"
                name="contrasena"
                minlength="8"
                maxlength="72"
                autocomplete="new-password"
                required>

            <button type="submit">Registrarse</button>
        </form>

        <p>¿Ya tiene una cuenta? <a href="login.php">Iniciar sesión</a></p>
    </main>

</body>

</html>