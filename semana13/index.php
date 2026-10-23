<?php

// se inicializan las variables del formulario
$nombre = "";
$correo = "";
$curso = "";
$mensaje = "";
$tipoMensaje = "";
$inscripcion = null;

// se definen los cursos disponibles
$cursosDisponibles = [
    "php" => "Desarrollo con PHP",
    "javascript" => "JavaScript",
    "diseno" => "Diseño web"
];

// se comprueba si se envio el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // se reciben los datos como texto
    $nombre = is_string($_POST["nombre"] ?? null)
        ? trim($_POST["nombre"])
        : "";

    $correo = is_string($_POST["correo"] ?? null)
        ? trim($_POST["correo"])
        : "";

    $curso = is_string($_POST["curso"] ?? null)
        ? $_POST["curso"]
        : "";

    // se valida el nombre
    if (
        $nombre === "" ||
        strlen($nombre) > 100
    ) {
        $mensaje = "El nombre es obligatorio y no debe superar los 100 caracteres";
        $tipoMensaje = "error";

        // se valida el correo
    } elseif (
        strlen($correo) > 254 ||
        !filter_var($correo, FILTER_VALIDATE_EMAIL)
    ) {
        $mensaje = "Ingrese un correo electronico valido";
        $tipoMensaje = "error";

        // se valida que el curso exista en la lista permitida
    } elseif (!array_key_exists($curso, $cursosDisponibles)) {
        $mensaje = "Seleccione un curso valido";
        $tipoMensaje = "error";
    } else {
        // se prepara el resumen de la inscripcion
        $inscripcion = [
            "nombre" => $nombre,
            "correo" => $correo,
            "curso" => $cursosDisponibles[$curso]
        ];

        // se confirma que los datos fueron procesados
        $mensaje = "La inscripcion se proceso correctamente";
        $tipoMensaje = "exito";
    }
}

// se prepara una funcion para mostrar texto de forma segura
function escapar(string $texto): string
{
    return htmlspecialchars(
        $texto,
        ENT_QUOTES,
        "UTF-8"
    );
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inscripcion a cursos</title>

    <!-- se incorpora la hoja de estilos -->
    <link rel="stylesheet" href="estilos.css">

    <!-- se incorpora javascript despues de interpretar el html -->
    <script src="script.js" defer></script>
</head>

<body>

    <main class="contenedor">

        <header class="encabezado">
            <p class="etiqueta">FORMACION PROFESIONAL</p>
            <h1>Inscripcion a cursos</h1>
            <p>Complete los datos para procesar su inscripcion</p>
        </header>

        <?php if ($mensaje !== ""): ?>
            <!-- se muestra el mensaje generado por php -->
            <div
                class="mensaje <?= escapar($tipoMensaje) ?>"
                role="status"
                aria-live="polite">
                <?= escapar($mensaje) ?>
            </div>
        <?php endif; ?>

        <form id="formularioInscripcion" method="POST" action="index.php">

            <div class="campo">
                <label for="nombre">Nombre completo</label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    maxlength="100"
                    autocomplete="name"
                    required
                    value="<?= escapar($nombre) ?>">

                <small id="errorNombre" class="error-campo"></small>
            </div>

            <div class="campo">
                <label for="correo">Correo electronico</label>

                <input
                    type="email"
                    id="correo"
                    name="correo"
                    maxlength="254"
                    autocomplete="email"
                    required
                    value="<?= escapar($correo) ?>">

                <small id="errorCorreo" class="error-campo"></small>
            </div>

            <div class="campo">
                <label for="curso">Curso de interes</label>

                <select id="curso" name="curso" required>
                    <option value="">Seleccione un curso</option>

                    <?php foreach ($cursosDisponibles as $codigo => $nombreCurso): ?>
                        <option
                            value="<?= escapar($codigo) ?>"
                            <?= $curso === $codigo ? "selected" : "" ?>>
                            <?= escapar($nombreCurso) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <small id="errorCurso" class="error-campo"></small>
            </div>

            <button type="submit">Procesar inscripcion</button>

        </form>

        <?php if ($inscripcion !== null): ?>
            <!-- se genera el resumen con los datos procesados por php -->
            <section class="resultado" aria-labelledby="tituloResultado">
                <h2 id="tituloResultado">Resumen de inscripcion</h2>

                <p>
                    <strong>Nombre:</strong>
                    <?= escapar($inscripcion["nombre"]) ?>
                </p>

                <p>
                    <strong>Correo:</strong>
                    <?= escapar($inscripcion["correo"]) ?>
                </p>

                <p>
                    <strong>Curso:</strong>
                    <?= escapar($inscripcion["curso"]) ?>
                </p>
            </section>
        <?php endif; ?>

        <footer>
            <p>Aplicacion educativa desarrollada con PHP y JavaScript</p>
        </footer>

    </main>

</body>

</html>