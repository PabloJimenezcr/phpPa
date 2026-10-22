<?php

// se aceptan solamente solicitudes post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// se reciben los valores del formulario
$nombreRecibido = $_POST["nombre"] ?? "";
$categoriaRecibida = $_POST["categoria"] ?? "";
$precioRecibido = $_POST["precio"] ?? "";
$stockRecibido = $_POST["stock"] ?? "";

// se verifica que los campos de texto sean cadenas
$nombre = is_string($nombreRecibido)
    ? trim($nombreRecibido)
    : "";

$categoria = is_string($categoriaRecibida)
    ? trim($categoriaRecibida)
    : "";

// se valida el formato del precio
$precioValido = is_string($precioRecibido)
    && $precioRecibido !== ""
    && is_numeric($precioRecibido);

// se valida el formato del stock
$stockValido = is_string($stockRecibido)
    && preg_match('/^(0|[1-9][0-9]*)$/', $stockRecibido) === 1;

if (!$precioValido || !$stockValido) {
    exit("El precio o el stock no tienen un formato valido");
}

// se convierten los valores numericos
$precio = $precioValido ? (float) $precioRecibido : 0;
$stock = $stockValido ? (int) $stockRecibido : 0;

// se comprueba que los datos cumplan las reglas
if (
    $nombre === "" ||
    strlen($nombre) > 100 ||
    $categoria === "" ||
    strlen($categoria) > 60 ||
    !$precioValido ||
    $precio <= 0 ||
    $precio > 99999999.99 ||
    !$stockValido ||
    $stock > 2147483647
) {
    exit("Los datos ingresados no son validos");
}

try {
    // se incluye la conexion
    require_once __DIR__ . "/config/conexion.php";

    // se prepara la consulta de insercion
    $sql = "INSERT INTO productos (nombre, categoria, precio, stock)
            VALUES (:nombre, :categoria, :precio, :stock)";

    $consulta = $conexion->prepare($sql);

    // se ejecuta la consulta con los valores
    $consulta->execute([
        ":nombre" => $nombre,
        ":categoria" => $categoria,
        ":precio" => number_format($precio, 2, ".", ""),
        ":stock" => $stock
    ]);

    // se regresa al listado con el mensaje de exito
    header("Location: index.php?mensaje=creado");
    exit;
} catch (PDOException $error) {
    // se registra el detalle tecnico
    error_log($error->getMessage());

    // se muestra un mensaje general
    exit("No fue posible guardar el producto");
}
