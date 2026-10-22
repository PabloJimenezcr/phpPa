<?php

// se inicia la sesion
session_start();

// se comprueba que exista una sesion autenticada
if (!isset($_SESSION["usuario_id"])) {
    // se redirige al inicio de sesion
    header("Location: login.php");
    exit;
}

// se obtiene el nombre de forma segura
$nombre = is_string($_SESSION["usuario_nombre"] ?? null)
    ? $_SESSION["usuario_nombre"]
    : "Usuario";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área privada</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <main class="contenedor">
        <h1>Área privada</h1>

        <p>
            Bienvenido,
            <?= htmlspecialchars($nombre, ENT_QUOTES, "UTF-8") ?>
        </p>

        <p>El acceso está protegido mediante una sesión PHP.</p>

        <a class="boton" href="logout.php">Cerrar sesión</a>
    </main>

</body>

</html>