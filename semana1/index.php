
<?php
// se define el nombre del curso
$nombreCurso = "fundamentos de php";

// se define el nombre del docente
$nombreDocente = "Francisco Jiménez Bonilla";

// se obtiene la fecha actual del servidor
$fechaActual = date("d/m/Y");

// se define el mensaje de bienvenida
$mensaje = "bienvenidos al aprendizaje de php";

// se crea una lista con los temas que se estudiaran
$temas = [
    "introduccion a php",
    "variables y tipos de datos",
    "integracion con html",
    "estilos con css",
    "generacion de contenido dinamico"
];

// se cuenta la cantidad de temas registrados
$totalTemas = count($temas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <!-- se configura la visualizacion en dispositivos moviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- se define el titulo que aparece en la pestana -->
    <title>curso de php</title>

    <!-- se conecta la hoja de estilos externa -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- se crea el encabezado principal de la pagina -->
    <header class="encabezado">
        <p class="etiqueta">formacion en desarrollo web</p>

        <h1><?php echo htmlspecialchars($nombreCurso); ?></h1>

        <p>una introduccion al desarrollo web dinamico</p>
    </header>

    <!-- se crea el contenido principal de la pagina -->
    <main class="contenedor">

        <!-- se muestra el mensaje generado desde php -->
        <section class="tarjeta bienvenida">
            <h2>
                <?php echo htmlspecialchars($mensaje); ?>
            </h2>

            <p>
                en este espacio se presentan los datos
                principales del curso
            </p>
        </section>

        <!-- se presentan los datos del curso -->
        <section class="tarjeta">
            <h2>informacion del curso</h2>

            <p>
                <strong>docente</strong>

                <?php echo htmlspecialchars($nombreDocente); ?>
            </p>

            <p>
                <strong>fecha del servidor</strong>

                <?php echo htmlspecialchars($fechaActual); ?>
            </p>

            <p>
                <strong>cantidad de temas</strong>

                <?php echo $totalTemas; ?>
            </p>
        </section>

        <!-- se recorren los temas almacenados en el arreglo -->
        <section class="tarjeta">
            <h2>temas de aprendizaje</h2>

            <ul>
                <?php foreach ($temas as $tema): ?>

                    <!-- se muestra cada tema de la lista -->
                    <li>
                        <?php echo htmlspecialchars($tema); ?>
                    </li>

                <?php endforeach; ?>
            </ul>
        </section>

    </main>

    <!-- se muestra el pie de pagina -->
    <footer class="pie">
        <p>practica de fundamentos de php</p>
    </footer>

</body>
</html>
