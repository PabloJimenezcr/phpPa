<?php

// se aceptan solamente solicitudes post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// se reciben los datos como texto
$nombreRecibido = $_POST["nombre"] ?? "";
$correoRecibido = $_POST["correo"] ?? "";

// se verifica que los datos sean cadenas de texto
$nombre = is_string($nombreRecibido)
    ? trim($nombreRecibido)
    : "";

$correo = is_string($correoRecibido)
    ? trim($correoRecibido)
    : "";

// se valida que los campos sean correctos
if (
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

    // se prepara la consulta de insercion
    $sql = "INSERT INTO estudiantes (nombre, correo)
            VALUES (:nombre, :correo)";

    $consulta = $conexion->prepare($sql);

    // se ejecuta la consulta con los datos recibidos
    $consulta->execute([
        ":nombre" => $nombre,
        ":correo" => $correo
    ]);

    // se regresa al listado despues de guardar
    header("Location: index.php");
    exit;
} catch (PDOException $error) {
    // se registra el detalle tecnico
    error_log($error->getMessage());

    // se muestra un mensaje general
    exit("No fue posible guardar el estudiante. Verifique que el correo no este registrado");
}
