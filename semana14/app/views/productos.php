
<?php

// se carga la configuracion si todavia no existe
if (!defined("APP_NAME")) {
    require_once __DIR__ . "/../config/config.php";
}

// se carga la lista de productos si no existe
if (!isset($productos) || !is_array($productos)) {
    $productos = require __DIR__ . "/../data/productos.php";
}

// se inicializa la busqueda si no existe
if (!isset($busqueda)) {
    $busqueda = is_string($_GET["buscar"] ?? null)
        ? trim($_GET["buscar"])
        : "";
}

// se filtran los productos cuando existe una busqueda
if ($busqueda !== "") {
    $productos = array_filter(
        $productos,
        function ($producto) use ($busqueda) {
            return stripos($producto["nombre"], $busqueda) !== false
                || stripos($producto["categoria"], $busqueda) !== false;
        }
    );
}

// se reorganizan los indices del arreglo
$productos = array_values($productos);

// se calcula la cantidad de resultados
$totalProductos = count($productos);

// se prepara una funcion para mostrar texto de forma segura
if (!function_exists("escapar")) {
    function escapar(string $valor): string
    {
        return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
    }
}

?>

<section class="catalogo">

    <header class="encabezado">
        <p>CATALOGO DE PRODUCTOS</p>
        <h1><?= escapar(APP_NAME) ?></h1>
        <p>Productos disponibles en nuestro catalogo</p>
    </header>

    <form method="GET" action="index.php" class="buscador">
        <label for="buscar">Buscar productos</label>

        <input
            type="search"
            id="buscar"
            name="buscar"
            placeholder="Nombre o categoria"
            value="<?= escapar($busqueda) ?>">

        <button type="submit">Buscar</button>
    </form>

    <p class="contador">
        Resultados encontrados: <?= $totalProductos ?>
    </p>

    <?php if ($totalProductos === 0): ?>

        <p class="sin-resultados">
            No se encontraron productos con esa busqueda
        </p>

    <?php else: ?>

        <div class="productos">

            <?php foreach ($productos as $producto): ?>

                <article class="tarjeta">

                    <span class="categoria">
                        <?= escapar((string) $producto["categoria"]) ?>
                    </span>

                    <h2>
                        <?= escapar((string) $producto["nombre"]) ?>
                    </h2>

                    <p class="precio">
                        ₡<?= number_format((float) $producto["precio"], 0, ",", ".") ?>
                    </p>

                    <p class="<?= !empty($producto["disponible"]) ? "disponible" : "agotado" ?>">
                        <?= !empty($producto["disponible"]) ? "Disponible" : "Agotado" ?>
                    </p>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>
