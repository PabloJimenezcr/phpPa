<?php

// se inicia el manejo de errores de la pagina
$errorGeneral = "";

// se intenta consultar la base de datos
try {
    // se incluye la conexion
    require_once __DIR__ . "/config/conexion.php";

    // se prepara la consulta de estudiantes
    $consulta = $conexion->prepare(
        "SELECT id, nombre, correo, fecha_registro
         FROM estudiantes
         ORDER BY id DESC"
    );

    // se ejecuta la consulta
    $consulta->execute();

    // se recuperan los estudiantes
    $estudiantes = $consulta->fetchAll();
} catch (PDOException $error) {
    // se registra el detalle tecnico
    error_log($error->getMessage());

    // se prepara un mensaje general
    $errorGeneral = "No fue posible cargar los estudiantes";

    // se evita utilizar resultados inexistentes
    $estudiantes = [];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de estudiantes</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <main class="contenedor">
        <header class="encabezado">
            <p class="etiqueta">SEMANA 10 · PHP Y MYSQL</p>
            <h1>Gestión de estudiantes</h1>
            <p>Aplicación de ejemplo conectada a una base de datos</p>
        </header>

        <section class="panel">
            <h2>Registrar estudiante</h2>

            <form action="guardar.php" method="POST" class="formulario">
                <div class="campo">
                    <label for="nombre">Nombre completo</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        required>
                </div>

                <div class="campo">
                    <label for="correo">Correo electrónico</label>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        maxlength="150"
                        required>
                </div>

                <button type="submit">Guardar estudiante</button>
            </form>
        </section>

        <section class="panel">
            <div class="titulo-listado">
                <div>
                    <h2>Estudiantes registrados</h2>
                    <p>Registros almacenados en MySQL</p>
                </div>

                <span class="contador">
                    <?= count($estudiantes) ?> registros
                </span>
            </div>

            <?php if ($errorGeneral !== ""): ?>
                <p class="mensaje error">
                    <?= htmlspecialchars($errorGeneral, ENT_QUOTES, "UTF-8") ?>
                </p>
            <?php elseif (count($estudiantes) === 0): ?>
                <p class="mensaje">No hay estudiantes registrados</p>
            <?php else: ?>
                <div class="tabla-contenedor">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Fecha de registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($estudiantes as $estudiante): ?>
                                <tr>
                                    <td>
                                        <?= (int) $estudiante["id"] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($estudiante["nombre"], ENT_QUOTES, "UTF-8") ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($estudiante["correo"], ENT_QUOTES, "UTF-8") ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($estudiante["fecha_registro"], ENT_QUOTES, "UTF-8") ?>
                                    </td>

                                    <td class="acciones">
                                        <a
                                            class="boton-editar"
                                            href="editar.php?id=<?= (int) $estudiante["id"] ?>">
                                            Editar
                                        </a>

                                        <form
                                            action="eliminar.php"
                                            method="POST"
                                            onsubmit="return confirm('¿Desea eliminar este estudiante?')">
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $estudiante["id"] ?>">

                                            <button
                                                type="submit"
                                                class="boton-eliminar">
                                                Eliminar
                                            </button>
                                        </form>
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