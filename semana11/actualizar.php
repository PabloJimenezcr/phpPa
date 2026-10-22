<?php

// se aceptan solamente solicitudes post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// se reciben los valores del formulario
$idRecibido = $_POST["id"] ?? "";
$nombreRecibido = $_POST["nombre"] ?? "";
$categoriaRecibida = $_POST["categoria"] ?? "";
$precioRecibido = $_POST["precio"] ?? "";
$stockRecibido = $_POST["stock"] ?? "";

// se valida el identificador
$id = filter_var(
    $idRecibido,
    FILTER_VALIDATE_INT,
    ["options" => ["min_range" => 1]]
);

// se validan los campos de texto
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

// se convierten los valores numericos
$precio = $precioValido ? (float) $precioRecibido : 0;
$stock = $stockValido ? (int) $stockRecibido : 0;

// se validan todos los datos recibidos
if (
    $id === false ||
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

    // se prepara la consulta de actualizacion
    $sql = "UPDATE productos
            SET nombre = :nombre,
                categoria = :categoria,
                precio = :precio,
                stock = :stock
            WHERE id = :id";

    $consulta = $conexion->prepare($sql);

    // se ejecuta la consulta con los valores
    $consulta->execute([
        ":nombre" => $nombre,
        ":categoria" => $categoria,
        ":precio" => number_format($precio, 2, ".", ""),
        ":stock" => $stock,
        ":id" => $id
    ]);

    // se regresa al listado con el mensaje de exito
    header("Location: index.php?mensaje=actualizado");
    exit;
} catch (PDOException $error) {
    // se registra el detalle tecnico
    error_log($error->getMessage());

    // se muestra un mensaje general
    exit("No fue posible actualizar el producto");
}
