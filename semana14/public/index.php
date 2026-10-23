<?php

// se carga la configuracion general
require_once __DIR__ . "/../app/config/config.php";

// se carga el controlador que prepara los productos
require_once __DIR__ . "/../app/controllers/ProductoController.php";

// se comprueba que existan las variables necesarias
if (!isset($productos, $busqueda, $totalProductos)) {
    exit("Error: el controlador no preparo las variables necesarias");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars(APP_NAME, ENT_QUOTES, "UTF-8") ?></title>

    <!-- se carga la hoja de estilos del catalogo -->
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <!-- se define el contenido principal de la aplicacion -->
    <main class="contenedor">

        <?php
        // se carga la vista que presenta los productos
        require __DIR__ . "/../app/views/productos.php";
        ?>

    </main>

</body>

</html>