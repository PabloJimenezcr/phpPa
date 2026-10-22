<?php

// se define la ruta del archivo json
$rutaArchivo = __DIR__ . "/productos.json";

// se inicializa el arreglo de productos
$productos = [];

// se inicializa el mensaje de error
$error = "";

// se verifica que el archivo exista
if (!is_file($rutaArchivo)) {

    // se informa que el archivo no existe
    $error = "No se encontro el archivo del catalogo";
} elseif (!is_readable($rutaArchivo)) {

    // se informa que el archivo no se puede leer
    $error = "No se puede leer el archivo del catalogo";
} else {

    // se lee el contenido del archivo json
    $contenido = file_get_contents($rutaArchivo);

    // se verifica que la lectura haya funcionado
    if ($contenido === false) {

        // se informa que ocurrio un error de lectura
        $error = "Ocurrio un error al leer los datos";
    } else {

        // se convierte el json en un arreglo asociativo
        $datos = json_decode($contenido, true);

        // se verifica que el contenido json sea valido
        if (json_last_error() !== JSON_ERROR_NONE) {

            // se informa que el archivo tiene un formato incorrecto
            $error = "El archivo del catalogo contiene un json invalido";
        } elseif (!is_array($datos) || !array_is_list($datos)) {

            // se verifica que la estructura principal sea una lista
            $error = "La estructura del catalogo no es valida";
        } else {

            // se recorren los registros para validar su estructura
            foreach ($datos as $producto) {

                // se comprueba que cada producto sea un arreglo
                if (
                    !is_array($producto) ||
                    !isset(
                        $producto["id"],
                        $producto["nombre"],
                        $producto["categoria"],
                        $producto["precio"],
                        $producto["disponible"],
                        $producto["descripcion"]
                    ) ||
                    !is_int($producto["id"]) ||
                    !is_string($producto["nombre"]) ||
                    !is_string($producto["categoria"]) ||
                    !(is_int($producto["precio"]) || is_float($producto["precio"])) ||
                    !is_bool($producto["disponible"]) ||
                    !is_string($producto["descripcion"]) ||
                    $producto["precio"] < 0
                ) {

                    // se informa que un producto tiene datos incorrectos
                    $error = "Uno o mas productos tienen datos invalidos";

                    // se detiene la validacion de los productos
                    break;
                }
            }

            // se guardan los datos cuando no existen errores
            if ($error === "") {
                $productos = $datos;
            }
        }
    }
}

// se calcula la cantidad de productos validos
$totalProductos = count($productos);

// se cuenta la cantidad de productos disponibles
$totalDisponibles = count(
    array_filter(
        $productos,
        fn($producto) => $producto["disponible"] === true
    )
);

// se calcula el precio total de los productos
$valorCatalogo = array_sum(
    array_column($productos, "precio")
);

// se define una funcion para escapar el texto html
function escapar(string $valor): string
{
    return htmlspecialchars(
        $valor,
        ENT_QUOTES | ENT_SUBSTITUTE,
        "UTF-8"
    );
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catálogo de productos</title>

    <!-- se conecta la hoja de estilos -->
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <!-- se crea el encabezado del catalogo -->
    <header class="encabezado">
        <div class="contenedor">
            <p class="etiqueta">TIENDA DEMO</p>
            <h1>Catálogo de productos</h1>
            <p>Información cargada desde un archivo JSON</p>
        </div>
    </header>

    <main class="contenedor contenido">

        <?php if ($error !== ""): ?>

            <!-- se muestra el error cuando no se pueden cargar los datos -->
            <section class="mensaje error">
                <h2>No fue posible cargar el catálogo</h2>
                <p><?= escapar($error) ?></p>
            </section>

        <?php else: ?>

            <!-- se muestran los indicadores del catalogo -->
            <section class="resumen">

                <article class="indicador">
                    <span>Total de productos</span>
                    <strong><?= $totalProductos ?></strong>
                </article>

                <article class="indicador">
                    <span>Productos disponibles</span>
                    <strong><?= $totalDisponibles ?></strong>
                </article>

                <article class="indicador">
                    <span>Valor del catálogo</span>
                    <strong>
                        ₡<?= number_format($valorCatalogo, 0, ",", ".") ?>
                    </strong>
                </article>

            </section>

            <section class="seccion-catalogo">

                <div class="titulo-seccion">
                    <h2>Nuestros productos</h2>
                    <p>Productos registrados en el archivo de datos</p>
                </div>

                <?php if ($totalProductos === 0): ?>

                    <!-- se muestra un mensaje si no hay productos -->
                    <p class="mensaje">
                        No hay productos registrados en el catálogo
                    </p>

                <?php else: ?>

                    <!-- se genera una tarjeta por cada producto -->
                    <div class="catalogo">

                        <?php foreach ($productos as $producto): ?>

                            <article class="producto">

                                <div class="producto-cabecera">
                                    <span class="categoria">
                                        <?= escapar($producto["categoria"]) ?>
                                    </span>

                                    <?php if ($producto["disponible"]): ?>

                                        <span class="estado disponible">
                                            Disponible
                                        </span>

                                    <?php else: ?>

                                        <span class="estado agotado">
                                            Agotado
                                        </span>

                                    <?php endif; ?>
                                </div>

                                <h3>
                                    <?= escapar($producto["nombre"]) ?>
                                </h3>

                                <p class="descripcion">
                                    <?= escapar($producto["descripcion"]) ?>
                                </p>

                                <div class="producto-pie">
                                    <strong class="precio">
                                        ₡<?= number_format(
                                                $producto["precio"],
                                                0,
                                                ",",
                                                "."
                                            ) ?>
                                    </strong>

                                    <span class="identificador">
                                        Código <?= $producto["id"] ?>
                                    </span>
                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </section>

        <?php endif; ?>

    </main>

    <!-- se muestra el pie de pagina -->
    <footer class="pie">
        <p>Catálogo desarrollado con PHP y JSON</p>
    </footer>

</body>

</html>