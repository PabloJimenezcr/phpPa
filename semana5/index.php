<?php
// se carga el archivo que contiene los productos
require_once __DIR__ . "/productos.php";

// se obtiene la cantidad total de productos
$cantidadProductos = count($productos);

// se extraen todos los precios de los productos
$precios = array_column($productos, "precio");

// se calcula la suma de todos los precios
$totalPrecios = array_sum($precios);

// se cuentan los productos disponibles
$productosDisponibles = array_filter(
    $productos,
    fn($producto) => $producto["disponible"] === true
);

// se prepara una copia para ordenar los productos por precio
$productosOrdenados = $productos;

// se ordenan los productos de menor a mayor precio
usort(
    $productosOrdenados,
    fn($a, $b) => $a["precio"] <=> $b["precio"]
);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo de productos</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <header class="encabezado">
        <p class="etiqueta">TECNOLOGIA PARA TODOS</p>
        <h1>Catalogo de productos</h1>
        <p>Explora nuestros productos disponibles</p>
    </header>

    <main class="contenedor">

        <section class="resumen">

            <article class="indicador">
                <span>Productos registrados</span>
                <strong><?= $cantidadProductos ?></strong>
            </article>

            <article class="indicador">
                <span>Productos disponibles</span>
                <strong><?= count($productosDisponibles) ?></strong>
            </article>

            <article class="indicador">
                <span>Suma de precios</span>
                <strong>
                    ₡<?= number_format($totalPrecios, 0, ",", ".") ?>
                </strong>
            </article>

        </section>

        <section class="introduccion">
            <h2>Nuestro catalogo</h2>
            <p>
                Los productos se ordenan automaticamente
                de menor a mayor precio
            </p>
        </section>

        <section class="catalogo">

            <?php foreach ($productosOrdenados as $producto): ?>

                <article class="tarjeta">

                    <div class="icono-producto">
                        <?= htmlspecialchars(substr($producto["nombre"], 0, 1)) ?>
                    </div>

                    <span class="categoria">
                        <?= htmlspecialchars($producto["categoria"]) ?>
                    </span>

                    <h3>
                        <?= htmlspecialchars($producto["nombre"]) ?>
                    </h3>

                    <p class="descripcion">
                        <?= htmlspecialchars($producto["descripcion"]) ?>
                    </p>

                    <p class="precio">
                        ₡<?= number_format($producto["precio"], 0, ",", ".") ?>
                    </p>

                    <?php if ($producto["disponible"]): ?>

                        <span class="estado disponible">
                            Disponible
                        </span>

                    <?php else: ?>

                        <span class="estado agotado">
                            No disponible
                        </span>

                    <?php endif; ?>

                </article>

            <?php endforeach; ?>

        </section>

        <footer>
            <p>Catalogo desarrollado con PHP y arreglos</p>
        </footer>

    </main>

</body>

</html>