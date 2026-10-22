<?php

// se inicia o recupera la sesion
session_start();

// se verifica que el usuario haya iniciado sesion
if (($_SESSION["autenticado"] ?? false) !== true) {

    // se redirige a la pagina de inicio de sesion
    header("Location: index.php");
    exit;
}

// se recupera el nombre guardado en la sesion
$usuario = $_SESSION["usuario"] ?? "Usuario";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Área privada</title>

    <!-- se conecta la hoja de estilos -->
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <!-- se crea el contenedor principal -->
    <main class="contenedor">

        <!-- se muestra el contenido protegido -->
        <section class="tarjeta privada">

            <div class="icono">✓</div>

            <h1>Área privada</h1>

            <p>
                Bienvenido,
                <strong>
                    <?= htmlspecialchars($usuario, ENT_QUOTES, "UTF-8") ?>
                </strong>
            </p>

            <p class="descripcion">
                El acceso fue autorizado porque existe una sesion autenticada
            </p>

            <div class="informacion">
                <h2>Panel del estudiante</h2>

                <p>Esta página solamente debe estar disponible después de iniciar sesión</p>
            </div>

            <!-- se ofrece la opcion de cerrar sesion -->
            <a class="boton" href="cerrar_sesion.php">
                Cerrar sesión
            </a>

        </section>

    </main>

</body>

</html>