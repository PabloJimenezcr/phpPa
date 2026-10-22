<?php
// se define la nota minima para aprobar
const NOTA_MINIMA = 70;

// se define la nota maxima permitida
const NOTA_MAXIMA = 100;

// se establece el valor inicial del nombre
$nombre = "";

// se establece el valor inicial de la nota
$nota = "";

// se prepara el mensaje inicial
$mensaje = "complete el formulario para clasificar";

// se prepara la clasificacion inicial
$clasificacion = "sin clasificar";

// se prepara el resultado inicial
$resultado = "pendiente";

// se prepara el porcentaje inicial
$porcentaje = 0;

// se indica que todavia no hay un resultado calculado
$procesado = false;

// se prepara el mensaje de error
$error = "";

// se comprueba si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // se obtiene el nombre enviado o un texto vacio
    $nombre = trim($_POST["nombre"] ?? "");

    // se obtiene la nota enviada o un valor vacio
    $nota = trim($_POST["nota"] ?? "");

    // se comprueba que el nombre no este vacio
    if ($nombre === "") {

        // se informa que el nombre es obligatorio
        $error = "debe ingresar el nombre de la persona estudiante";

        // se comprueba que la nota no este vacia
    } elseif ($nota === "") {

        // se informa que la nota es obligatoria
        $error = "debe ingresar una nota";

        // se comprueba que la nota sea un valor numerico
    } elseif (!is_numeric($nota)) {

        // se informa que la nota debe ser numerica
        $error = "la nota debe contener un valor numerico";
    } else {

        // se convierte la nota a un numero decimal
        $nota = (float) $nota;

        // se comprueba que la nota este dentro del rango permitido
        if ($nota < 0 || $nota > NOTA_MAXIMA) {

            // se informa que la nota esta fuera del rango
            $error = "la nota debe estar entre 0 y 100";
        } else {

            // se calcula el porcentaje obtenido
            $porcentaje = ($nota / NOTA_MAXIMA) * 100;

            // se determina la clasificacion academica
            if ($nota >= 90) {

                // se asigna la clasificacion excelente
                $clasificacion = "excelente";
            } elseif ($nota >= 80) {

                // se asigna la clasificacion muy bueno
                $clasificacion = "muy bueno";
            } elseif ($nota >= NOTA_MINIMA) {

                // se asigna la clasificacion bueno
                $clasificacion = "bueno";
            } else {

                // se asigna la clasificacion insuficiente
                $clasificacion = "insuficiente";
            }

            // se determina si la persona aprobo
            $resultado = $nota >= NOTA_MINIMA ? "aprobado" : "reprobado";

            // se indica que el calculo fue completado
            $procesado = true;

            // se prepara un mensaje con el resultado obtenido
            $mensaje = "la evaluacion fue procesada correctamente";
        }
    }
}

// se define un mensaje alternativo para el nombre
$nombreMostrar = $nombre !== "" ? $nombre : "sin nombre";

// se prepara el nombre para mostrarlo de forma segura en html
$nombreSeguro = htmlspecialchars($nombreMostrar, ENT_QUOTES, "UTF-8");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <!-- se adapta la pagina a dispositivos moviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- se establece el titulo del sistema -->
    <title>sistema de clasificacion</title>

    <!-- se conecta la hoja de estilos -->
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <!-- se presenta el encabezado principal -->
    <header class="encabezado">
        <p class="etiqueta">practica de php</p>
        <h1>sistema de clasificacion</h1>
        <p>evaluacion y resultados academicos</p>
    </header>

    <!-- se organiza el contenido principal -->
    <main class="contenedor">

        <!-- se crea el formulario de entrada -->
        <section class="tarjeta">
            <h2>datos de la persona estudiante</h2>

            <p>
                ingrese los datos para calcular la clasificacion
            </p>

            <form method="POST" action="">

                <!-- se solicita el nombre de la persona -->
                <div class="campo">
                    <label for="nombre">nombre completo</label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="<?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="ejemplo: ana rodriguez"
                        maxlength="100"
                        required>
                </div>

                <!-- se solicita la nota de la evaluacion -->
                <div class="campo">
                    <label for="nota">nota obtenida</label>

                    <input
                        type="number"
                        id="nota"
                        name="nota"
                        value="<?php echo htmlspecialchars((string) $nota, ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="ejemplo: 85"
                        min="0"
                        max="100"
                        step="any"
                        required>

                    <small>ingrese un valor entre 0 y 100</small>
                </div>

                <!-- se envia la informacion al servidor -->
                <button type="submit">clasificar resultado</button>

            </form>
        </section>

        <!-- se muestran los errores de validacion -->
        <?php if ($error !== ""): ?>

            <section class="alerta error">
                <h2>no se pudo procesar</h2>
                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            </section>

        <?php endif; ?>

        <!-- se muestra el resultado cuando los datos son validos -->
        <?php if ($procesado): ?>

            <section class="tarjeta resultado">

                <p class="etiqueta">resultado de la evaluacion</p>

                <!-- se presenta el nombre de la persona estudiante -->
                <h2><?php echo $nombreSeguro; ?></h2>

                <!-- se presenta la nota calculada -->
                <div class="nota">
                    <?php echo number_format($nota, 2, ",", "."); ?>
                    <span>/ 100</span>
                </div>

                <!-- se presenta la clasificacion academica -->
                <p class="detalle">
                    <strong>clasificacion</strong>

                    <?php echo htmlspecialchars($clasificacion, ENT_QUOTES, 'UTF-8'); ?>
                </p>

                <!-- se presenta el resultado de aprobacion -->
                <p class="detalle">
                    <strong>resultado</strong>

                    <span class="<?php echo $resultado === 'aprobado' ? 'estado aprobado' : 'estado reprobado'; ?>">
                        <?php echo htmlspecialchars($resultado, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </p>

                <!-- se presenta el porcentaje obtenido -->
                <p class="detalle">
                    <strong>porcentaje</strong>

                    <?php echo number_format($porcentaje, 2, ",", "."); ?>%
                </p>

                <!-- se presenta el mensaje de confirmacion -->
                <p class="mensaje">
                    <?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
                </p>

            </section>

        <?php endif; ?>

        <!-- se presentan las reglas utilizadas para clasificar -->
        <section class="tarjeta">
            <h2>escala de clasificacion</h2>

            <div class="fila-escala">
                <span>excelente</span>
                <strong>90 a 100</strong>
            </div>

            <div class="fila-escala">
                <span>muy bueno</span>
                <strong>80 a 89,99</strong>
            </div>

            <div class="fila-escala">
                <span>bueno</span>
                <strong>70 a 79,99</strong>
            </div>

            <div class="fila-escala">
                <span>insuficiente</span>
                <strong>0 a 69,99</strong>
            </div>
        </section>

    </main>

    <!-- se presenta el pie de pagina -->
    <footer class="pie">
        <p>practica de operadores y estructuras de control</p>
    </footer>

</body>

</html>