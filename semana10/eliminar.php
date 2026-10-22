<?php

// se aceptan solamente solicitudes post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// se obtiene el identificador del formulario
$idRecibido = $_POST["id"] ?? "";

// se valida el identificador
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
    $consulta = $conexion->prepare(
        "DELETE FROM estudiantes WHERE id = :id"
    );

    // se ejecuta la consulta
    $consulta->execute([":id" => $id]);

    // se regresa al listado
    header("Location: index.php");
    exit;
} catch (PDOException $error) {
    // se registra el detalle tecnico
    error_log($error->getMessage());

    // se muestra un mensaje general
    exit("No fue posible eliminar el estudiante");
}
