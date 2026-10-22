<?php

// se obtiene el identificador del estudiante
$idRecibido = $_GET["id"] ?? "";

// se verifica que el identificador sea un numero entero positivo
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

    // se prepara la consulta del estudiante
    $consulta = $conexion->prepare(
        "SELECT id, nombre, correo
         FROM estudiantes
         WHERE id = :id"
    );

    // se ejecuta la consulta
    $consulta->execute([":id" => $id]);

    // se recupera el registro encontrado
    $estudiante = $consulta->fetch();

    // se verifica que el estudiante exista
    if (!$estudiante) {
        exit("El estudiante no existe");
    }
} catch (PDOException $error) {
    // se registra el detalle tecnico
    error_log($error->getMessage());

    // se muestra un mensaje general
    exit("No fue posible consultar el estudiante");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar estudiante</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <main class="contenedor">
        <section class="panel">
            <h1>Editar estudiante</h1>
            <p>Modifique los datos del registro seleccionado</p>

            <form action="actualizar.php" method="POST" class="formulario">
                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $estudiante["id"] ?>">

                <div class="campo">
                    <label for="nombre">Nombre completo</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        required
                        value="<?= htmlspecialchars($estudiante["nombre"], ENT_QUOTES, "UTF-8") ?>">
                </div>

                <div class="campo">
                    <label for="correo">Correo electrónico</label>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        maxlength="150"
                        required
                        value="<?= htmlspecialchars($estudiante["correo"], ENT_QUOTES, "UTF-8") ?>">
                </div>

                <button type="submit">Actualizar estudiante</button>

                <a class="enlace-volver" href="index.php">
                    Volver al listado
                </a>
            </form>
        </section>
    </main>

</body>

</html>