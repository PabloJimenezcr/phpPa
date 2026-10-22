<?php

// se inicia la sesion
session_start();

// se carga la conexion
require_once __DIR__ . "/config/conexion.php";

// se genera el token csrf
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// se inicializa el mensaje
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // se reciben los datos del formulario
    $correo = is_string($_POST["correo"] ?? null)
        ? trim($_POST["correo"])
        : "";

    $contrasena = is_string($_POST["contrasena"] ?? null)
        ? $_POST["contrasena"]
        : "";

    $token = is_string($_POST["csrf_token"] ?? null)
        ? $_POST["csrf_token"]
        : "";

    // se valida el token csrf
    if (
        !isset($_SESSION["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $token)
    ) {
        $mensaje = "La solicitud no es valida";
    } elseif (
        !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
        $contrasena === "" ||
        strlen($contrasena) > 72
    ) {
        $mensaje = "Correo o contraseña incorrectos";
    } else {
        // se busca el usuario mediante una consulta preparada
        $consulta = $conexion->prepare(
            "SELECT id, nombre, correo, password_hash
             FROM usuarios
             WHERE correo = :correo"
        );

        $consulta->execute([
            "correo" => $correo
        ]);

        $usuario = $consulta->fetch();

        // se verifica la contraseña contra el hash almacenado
        if (
            $usuario &&
            password_verify($contrasena, $usuario["password_hash"])
        ) {
            // se cambia el identificador de sesion tras autenticar
            session_regenerate_id(true);

            // se guarda solo la informacion necesaria para identificar al usuario
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nombre"] = $usuario["nombre"];

            // se renueva el token para la siguiente solicitud
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

            // se redirige a la pagina privada
            header("Location: privado.php");
            exit;
        } else {
            // se utiliza un mensaje generico para no revelar si existe el correo
            $mensaje = "Correo o contraseña incorrectos";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <main class="contenedor">
        <h1>Iniciar sesión</h1>

        <?php if ($mensaje !== ""): ?>
            <p class="mensaje">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>">

            <label for="correo">Correo electrónico</label>
            <input
                type="email"
                id="correo"
                name="correo"
                autocomplete="username"
                required>

            <label for="contrasena">Contraseña</label>
            <input
                type="password"
                id="contrasena"
                name="contrasena"
                autocomplete="current-password"
                required>

            <button type="submit">Entrar</button>
        </form>

        <p>¿No tiene una cuenta? <a href="registro.php">Registrarse</a></p>
    </main>

</body>

</html>