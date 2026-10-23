
<?php

// se carga la lista de productos
$productos = require __DIR__ . "/../data/productos.php";

// se obtiene el texto de busqueda
$busqueda = is_string($_GET["buscar"] ?? null)
    ? trim($_GET["buscar"])
    : "";

// se filtran los productos por nombre o categoria
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

// se calcula la cantidad de productos encontrados
$totalProductos = count($productos);
