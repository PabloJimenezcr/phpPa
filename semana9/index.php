
<?php

// se carga la clase producto
require_once __DIR__ . "/clases/Producto.php";

// se crean los objetos del catalogo
$productos = [
    new Producto(
        1,
        "Teclado mecanico",
        "Accesorios",
        25000,
        true,
        "Teclado ideal para estudiar y trabajar"
    ),

    new Producto(
        2,
        "Mouse inalambrico",
        "Accesorios",
        12000,
        true,
        "Mouse practico para el uso diario"
    ),

    new Producto(
        3,
        "Monitor de 24 pulgadas",
        "Pantallas",
        85000,
        true,
        "Monitor para trabajar y estudiar"
    ),

    new Producto(
        4,
        "Audifonos",
        "Audio",
        18000,
        false,
        "Audifonos para escuchar musica"
    )
];

// se define una funcion para escapar texto html
function escapar(string $texto): string
{
    return htmlspecialchars(
        $texto,
        ENT_QUOTES | ENT_SUBSTITUTE,
        "UTF-8"
    );
}

// se calcula la cantidad de productos
$totalProductos = count($productos);

// se cuentan los productos disponibles
$totalDisponibles = count(
    array_filter(
        $productos,
        fn($producto) => $producto->estaDisponible()
    )
);

// se calcula el valor total del catalogo
$valorCatalogo = array_sum(
    array_map(
        fn($producto) => $producto->obtenerPrecio(),
        $productos
    )
);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catálogo POO en PHP</title>

    <!-- se conecta la hoja de estilos -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- se muestra el encabezado de la pagina -->
    <header class="encabezado">
        <div class="contenedor">
            <p class="etiqueta">TIENDA DEMO</p>
            <h1>Catálogo de productos</h1>
            <p>Una aplicación creada con programación orientada a objetos</p>
        </div>
    </header>

    <main class="contenedor contenido">

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

        <!-- se genera el catalogo con los objetos -->
        <section>
            <div class="titulo-seccion">
                <h2>Nuestros productos</h2>
                <p>La informacion se obtiene de los objetos PHP</p>
            </div>

            <div class="catalogo">

                <?php foreach ($productos as $producto): ?>

                    <!-- se crea una tarjeta por producto -->
                    <article class="producto">

                        <div class="producto-cabecera">

                            <span class="categoria">
                                <?= escapar($producto->obtenerCategoria()) ?>
                            </span>

                            <?php if ($producto->estaDisponible()): ?>

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
                            <?= escapar($producto->obtenerNombre()) ?>
                        </h3>

                        <p class="descripcion">
                            <?= escapar($producto->obtenerDescripcion()) ?>
                        </p>

                        <div class="precio">
                            ₡<?= number_format(
                                $producto->obtenerPrecio(),
                                0,
                                ",",
                                "."
                            ) ?>
                        </div>

                        <div class="descuento">
                            Con descuento: ₡<?= number_format(
                                $producto->obtenerPrecioConDescuento(),
                                0,
                                ",",
                                "."
                            ) ?>
                        </div>

                        <p class="identificador">
                            Código <?= $producto->obtenerId() ?>
                        </p>

                    </article>

                <?php endforeach; ?>

            </div>
        </section>

    </main>

    <!-- se muestra el pie de pagina -->
    <footer class="pie">
        <p>Proyecto educativo de PHP y POO</p>
    </footer>

</body>
</html>
