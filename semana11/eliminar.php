<?php

// se aceptan solamente solicitudes post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// se recibe el identificador
$idRecibido = $_POST["id"] ?? "";

// se valida que el identificador sea positivo
$id = filter_var(
    $idRecibido,
    FILTER_VALIDATE_INT,
    ["options" => ["min_range" => 1]]
);

if ($id === false) {
    exit("El identificador no es valido");
}

try {
    // se incluye la conexion
    require_once __DIR__ . "/config/conexion.php";

    // se prepara la consulta de eliminacion
    $sql = "DELETE FROM productos WHERE id = :id";
    $consulta = $conexion->prepare($sql);

    // se ejecuta la consulta
    $consulta->execute([":id" => $id]);

    // se verifica si se elimino algun registro
    if ($consulta->rowCount() === 0) {
        header("Location: index.php");
        exit;
    }

    // se regresa al listado con el mensaje de exito
    header("Location: index.php?mensaje=eliminado");
    exit;
} catch (PDOException $error) {
    // se registra el detalle tecnico
    error_log($error->getMessage());

    // se muestra un mensaje general
    exit("No fue posible eliminar el producto");
}
