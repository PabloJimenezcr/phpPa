<?php

// se aceptan solamente solicitudes post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// se reciben los datos del formulario
$idRecibido = $_POST["id"] ?? "";
$nombreRecibido = $_POST["nombre"] ?? "";
$correoRecibido = $_POST["correo"] ?? "";

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

$correo = is_string($correoRecibido)
    ? trim($correoRecibido)
    : "";

// se comprueba que todos los datos sean validos
if (
    $id === false ||
    $nombre === "" ||
    strlen($nombre) > 100 ||
    $correo === "" ||
    strlen($correo) > 150 ||
    !filter_var($correo, FILTER_VALIDATE_EMAIL)
) {
    exit("Los datos ingresados no son validos");
}

try {
    // se incluye la conexion
    require_once __DIR__ . "/config/conexion.php";

    // se prepara la consulta de actualizacion
    $sql = "UPDATE estudiantes
            SET nombre = :nombre, correo = :correo
            WHERE id = :id";

    $consulta = $conexion->prepare($sql);

    // se ejecuta la consulta con sus parametros
    $consulta->execute([
        ":nombre" => $nombre,
        ":correo" => $correo,
        ":id" => $id
    ]);

    // se regresa al listado
    header("Location: index.php");
    exit;
} catch (PDOException $error) {
    // se registra el detalle tecnico
    error_log($error->getMessage());

    // se muestra un mensaje general
    exit("No fue posible actualizar el estudiante");
}
