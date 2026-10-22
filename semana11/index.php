<?php

// se inicializan los valores de la pagina
$errorGeneral = "";
$productos = [];
$busqueda = "";
$mensaje = "";

// se reciben los parametros de busqueda y mensaje
$busquedaRecibida = $_GET["buscar"] ?? "";
$mensajeRecibido = $_GET["mensaje"] ?? "";

// se valida el texto de busqueda
if (is_string($busquedaRecibida)) {
    $busqueda = trim($busquedaRecibida);
}

// se limita el mensaje a valores conocidos
if (is_string($mensajeRecibido)) {
    $mensajesPermitidos = [
        "creado" => "Producto registrado correctamente",
        "actualizado" => "Producto actualizado correctamente",
        "eliminado" => "Producto eliminado correctamente"
    ];

    if (isset($mensajesPermitidos[$mensajeRecibido])) {
        $mensaje = $mensajesPermitidos[$mensajeRecibido];
    }
}

try {
    // se incluye el archivo de conexion
    require_once __DIR__ . "/config/conexion.php";

    // se prepara la consulta segun exista una busqueda
    if ($busqueda !== "") {
        $sql = "SELECT id, nombre, categoria, precio, stock
                FROM productos
                WHERE nombre LIKE :busqueda
                ORDER BY id DESC";

        $consulta = $conexion->prepare($sql);

        // se ejecuta la consulta con el texto buscado
        $consulta->execute([
            ":busqueda" => "%" . $busqueda . "%"
        ]);
    } else {
        // se consultan todos los productos
        $sql = "SELECT id, nombre, categoria, precio, stock
                FROM productos
                ORDER BY id DESC";

        $consulta = $conexion->prepare($sql);
        $consulta->execute();
    }

    // se recuperan los productos encontrados
    $productos = $consulta->fetchAll();
} catch (PDOException $error) {
    // se registra el detalle tecnico
    error_log($error->getMessage());

    // se prepara un mensaje general
    $errorGeneral = "No fue posible consultar los productos";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracion de productos</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <main class="contenedor">
        <header class="encabezado">
            <p class="etiqueta">SEMANA 11 · PHP Y MYSQL</p>
            <h1>Administración de productos</h1>
            <p>Panel de gestión con operaciones CRUD</p>
        </header>

        <?php if ($mensaje !== ""): ?>
            <div class="mensaje exito">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <?php if ($errorGeneral !== ""): ?>
            <div class="mensaje error">
                <?= htmlspecialchars($errorGeneral, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <section class="panel">
            <h2>Registrar producto</h2>

            <form action="guardar.php" method="POST" class="formulario">
                <div class="campo">
                    <label for="nombre">Nombre del producto</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        required>
                </div>

                <div class="campo">
                    <label for="categoria">Categoría</label>
                    <input
                        type="text"
                        id="categoria"
                        name="categoria"
                        maxlength="60"
                        required>
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
                        required>
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
                        required>
                </div>

                <button type="submit">Guardar producto</button>
            </form>
        </section>

        <section class="panel">
            <div class="encabezado-listado">
                <div>
                    <h2>Listado de productos</h2>
                    <p><?= count($productos) ?> productos encontrados</p>
                </div>
            </div>

            <form action="index.php" method="GET" class="buscador">
                <label for="buscar">Buscar por nombre</label>

                <div class="buscador-fila">
                    <input
                        type="search"
                        id="buscar"
                        name="buscar"
                        maxlength="100"
                        placeholder="Ejemplo: teclado"
                        value="<?= htmlspecialchars($busqueda, ENT_QUOTES, "UTF-8") ?>">

                    <button type="submit">Buscar</button>

                    <a class="boton-secundario" href="index.php">
                        Limpiar
                    </a>
                </div>
            </form>

            <?php if ($errorGeneral !== ""): ?>
                <p>No se pudo cargar la información.</p>
            <?php elseif (count($productos) === 0): ?>
                <p class="mensaje">No se encontraron productos.</p>
            <?php else: ?>
                <div class="tabla-contenedor">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td>
                                        <?= (int) $producto["id"] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($producto["nombre"], ENT_QUOTES, "UTF-8") ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($producto["categoria"], ENT_QUOTES, "UTF-8") ?>
                                    </td>

                                    <td>
                                        ₡<?= number_format((float) $producto["precio"], 2, ",", ".") ?>
                                    </td>

                                    <td>
                                        <?= (int) $producto["stock"] ?>
                                    </td>

                                    <td>
                                        <div class="acciones">
                                            <a
                                                class="boton-editar"
                                                href="editar.php?id=<?= (int) $producto["id"] ?>">
                                                Editar
                                            </a>

                                            <form
                                                action="eliminar.php"
                                                method="POST"
                                                onsubmit="return confirm('¿Confirma la eliminacion de este producto?')">
                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int) $producto["id"] ?>">

                                                <button
                                                    type="submit"
                                                    class="boton-eliminar">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>

</body>

</html>