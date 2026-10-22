<?php

// se recibe el identificador del producto
$idRecibido = $_GET["id"] ?? "";

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

    // se prepara la consulta del producto
    $sql = "SELECT id, nombre, categoria, precio, stock
            FROM productos
            WHERE id = :id";

    $consulta = $conexion->prepare($sql);

    // se ejecuta la consulta con el identificador
    $consulta->execute([":id" => $id]);

    // se recupera el producto encontrado
    $producto = $consulta->fetch();

    // se comprueba que el producto exista
    if (!$producto) {
        http_response_code(404);
        exit("El producto solicitado no existe");
    }
} catch (PDOException $error) {
    // se registra el detalle tecnico
    error_log($error->getMessage());

    // se muestra un mensaje general
    exit("No fue posible consultar el producto");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <main class="contenedor">
        <section class="panel">
            <p class="etiqueta">GESTION DE PRODUCTOS</p>
            <h1>Editar producto</h1>
            <p>Modifique la información y guarde los cambios</p>

            <form action="actualizar.php" method="POST" class="formulario">
                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $producto["id"] ?>">

                <div class="campo">
                    <label for="nombre">Nombre del producto</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        required
                        value="<?= htmlspecialchars($producto["nombre"], ENT_QUOTES, "UTF-8") ?>">
                </div>

                <div class="campo">
                    <label for="categoria">Categoría</label>
                    <input
                        type="text"
                        id="categoria"
                        name="categoria"
                        maxlength="60"
                        required
                        value="<?= htmlspecialchars($producto["categoria"], ENT_QUOTES, "UTF-8") ?>">
                </div>

                <div class="campo">
                    <label for="precio">Precio en colones</label>
                    <input
                        type="number"
                        id="precio"
                        name="precio"
                        min="0.01"
                        max="99999999.99"
                        step="0.01"
                        required
                        value="<?= htmlspecialchars((string) $producto["precio"], ENT_QUOTES, "UTF-8") ?>">
                </div>

                <div class="campo">
                    <label for="stock">Cantidad disponible</label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        min="0"
                        max="2147483647"
                        step="1"
                        required
                        value="<?= (int) $producto["stock"] ?>">
                </div>

                <button type="submit">Guardar cambios</button>

                <a class="enlace-volver" href="index.php">
                    Volver al listado
                </a>
            </form>
        </section>
    </main>

</body>

</html>