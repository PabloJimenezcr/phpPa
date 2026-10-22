<?php
// se carga el archivo que contiene los datos
require_once __DIR__ . "/datos.php";

// se carga el archivo que contiene las funciones
require_once __DIR__ . "/funciones.php";

// se calcula la cantidad de cursos disponibles
$cantidadCursos = count($cursos);

// se calcula el precio acumulado de todos los cursos
$totalPrecios = array_sum(array_column($cursos, "precio"));
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo de cursos PHP</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <header class="encabezado">
        <p class="etiqueta">FORMACION PROFESIONAL</p>
        <h1>Catalogo de cursos</h1>
        <p>Aprende nuevas habilidades con nuestros cursos</p>
    </header>

    <main class="contenedor">

        <section class="resumen">
            <div>
                <span>Cursos disponibles</span>
                <strong><?= $cantidadCursos ?></strong>
            </div>

            <div>
                <span>Suma de precios originales</span>
                <strong><?= formatearPrecio($totalPrecios) ?></strong>
            </div>
        </section>

        <section class="catalogo">

            <?php foreach ($cursos as $curso): ?>

                <?php
                // se obtiene el precio original del curso
                $precio = $curso["precio"];

                // se calcula el impuesto del curso
                $impuesto = calcularImpuesto($precio);

                // se calcula el precio final del curso
                $precioFinal = calcularPrecioFinal($precio);

                // se calcula un descuento de ejemplo del cinco por ciento
                $descuento = $obtenerDescuento($precio);
                ?>

                <article class="tarjeta">

                    <div class="icono-curso">PHP</div>

                    <h2>
                        <?= htmlspecialchars($curso["nombre"]) ?>
                    </h2>

                    <p class="descripcion">
                        <?= htmlspecialchars($curso["descripcion"]) ?>
                    </p>

                    <div class="detalle">
                        <span>Precio original</span>
                        <strong>
                            <?= formatearPrecio($precio) ?>
                        </strong>
                    </div>

                    <div class="detalle">
                        <span>Impuesto del 13 %</span>
                        <strong>
                            <?= formatearPrecio($impuesto) ?>
                        </strong>
                    </div>

                    <div class="detalle">
                        <span>Descuento de ejemplo del 5 %</span>
                        <strong>
                            <?= formatearPrecio($descuento) ?>
                        </strong>
                    </div>

                    <div class="precio-final">
                        <span>Precio final con impuesto</span>
                        <strong>
                            <?= formatearPrecio($precioFinal) ?>
                        </strong>
                    </div>

                </article>

            <?php endforeach; ?>

        </section>

        <footer>
            <p>Catalogo desarrollado con PHP</p>
        </footer>

    </main>

</body>

</html>