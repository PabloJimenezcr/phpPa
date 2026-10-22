<?php

// se inicia o recupera la sesion actual
session_start();

// se configura el usuario de demostracion
$usuarioCorrecto = "estudiante";

// se configura la contrasena de demostracion
$contrasenaCorrecta = "Demo123!";

// se inicializa el mensaje de error
$error = "";

// se verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // se recuperan los datos enviados como texto
    $usuario = is_string($_POST["usuario"] ?? null)
        ? trim($_POST["usuario"])
        : "";

    $contrasena = is_string($_POST["contrasena"] ?? null)
        ? $_POST["contrasena"]
        : "";

    // se comprueban las credenciales de demostracion
    if (
        $usuario === $usuarioCorrecto &&
        $contrasena === $contrasenaCorrecta
    ) {

        // se renueva el identificador de sesion
        session_regenerate_id(true);

        // se guarda el estado de autenticacion
        $_SESSION["autenticado"] = true;

        // se guarda el nombre del usuario
        $_SESSION["usuario"] = $usuario;

        // se redirige a la pagina privada
        header("Location: privada.php");
        exit;
    } else {

        // se informa que las credenciales no son correctas
        $error = "Usuario o contrasena incorrectos";
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inicio de sesión</title>

    <!-- se conecta la hoja de estilos -->
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <!-- se crea el contenedor principal -->
    <main class="contenedor">

        <!-- se presenta el encabezado del formulario -->
        <section class="tarjeta">

            <h1>Iniciar sesión</h1>

            <p class="descripcion">
                Ingrese sus credenciales para continuar
            </p>

            <!-- se muestra el error cuando las credenciales no coinciden -->
            <?php if ($error !== ""): ?>
                <p class="mensaje error">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </p>
            <?php endif; ?>

            <!-- se crea el formulario de autenticacion -->
            <form action="index.php" method="POST">

                <label for="usuario">Usuario</label>

                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    required
                    autocomplete="username">

                <label for="contrasena">Contraseña</label>

                <input
                    type="password"
                    id="contrasena"
                    name="contrasena"
                    required
                    autocomplete="current-password">

                <button type="submit">Ingresar</button>

            </form>

            <!-- se muestran las credenciales de prueba -->
            <div class="credenciales">
                <p><strong>Usuario:</strong> estudiante</p>
                <p><strong>Contraseña:</strong> Demo123!</p>
            </div>

        </section>

    </main>

</body>

</html>