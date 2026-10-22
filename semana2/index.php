
<?php
// se define el nombre del sistema mediante una constante
const NOMBRE_SISTEMA = "perfil digital";

// se define el tipo de moneda utilizada en el ejemplo
const MONEDA = "CRC";

// se almacena el nombre de la persona
$nombre = "Daniel";

// se almacena el apellido de la persona
$apellido = "Mora";

// se almacena la edad como numero entero
$edad = 29;

// se almacena la estatura como numero decimal
$estatura = 1.78;

// se almacena la profesion de la persona
$profesion = "desarrollador web";

// se almacena un salario mensual de ejemplo
$salario = 850000.00;

// se indica si la persona esta activa
$activo = true;

// se almacena una lista de habilidades
$habilidades = [
    "html y css",
    "php",
    "mysql",
    "javascript"
];

// se declara el telefono sin un valor registrado
$telefono = null;

// se unen el nombre y el apellido
$nombreCompleto = $nombre . " " . $apellido;

// se crea un mensaje mediante interpolacion de variables
$mensaje = "bienvenido al perfil de {$nombreCompleto}";

// se comprueba si el telefono tiene un valor registrado
$telefonoRegistrado = !is_null($telefono);

// se comprueba si la edad es un numero entero
$edadValida = is_int($edad);

// se convierte la edad a texto para demostrar la conversion
$edadTexto = (string) $edad;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <!-- se adapta la pagina a dispositivos moviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- se establece el titulo de la pagina -->
    <title>ficha de perfil</title>

    <!-- se conecta el archivo de estilos -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- se crea el encabezado del sistema -->
    <header class="encabezado">
        <p><?php echo htmlspecialchars(NOMBRE_SISTEMA); ?></p>
        <h1>ficha de perfil</h1>
    </header>

    <!-- se crea el contenedor principal -->
    <main class="contenedor">

        <!-- se presenta la informacion principal de la persona -->
        <section class="perfil">

            <!-- se muestra el nombre completo mediante una variable -->
            <h2>
                <?php echo htmlspecialchars($nombreCompleto); ?>
            </h2>

            <!-- se muestra la profesion de la persona -->
            <p class="profesion">
                <?php echo htmlspecialchars($profesion); ?>
            </p>

            <!-- se muestra un mensaje generado mediante interpolacion -->
            <p>
                <?php echo htmlspecialchars($mensaje); ?>
            </p>

            <!-- se muestra el estado de actividad -->
            <span class="<?php echo $activo ? 'estado activo' : 'estado inactivo'; ?>">
                <?php echo $activo ? 'activo' : 'inactivo'; ?>
            </span>

        </section>

        <!-- se presentan los datos personales -->
        <section class="tarjeta">
            <h2>datos personales</h2>

            <p>
                <strong>edad</strong>
                <?php echo htmlspecialchars($edadTexto); ?> años
            </p>

            <p>
                <strong>estatura</strong>
                <?php echo htmlspecialchars((string) $estatura); ?> metros
            </p>

            <p>
                <strong>telefono</strong>

                <?php
                // se muestra el telefono si existe un valor registrado
                if ($telefonoRegistrado) {
                    echo htmlspecialchars((string) $telefono);
                } else {
                    echo "no registrado";
                }
                ?>
            </p>

            <p>
                <strong>validacion de edad</strong>

                <?php echo $edadValida ? "correcta" : "incorrecta"; ?>
            </p>
        </section>

        <!-- se presenta el salario mensual de ejemplo -->
        <section class="tarjeta">
            <h2>informacion profesional</h2>

            <p>
                <strong>salario mensual de ejemplo</strong>
            </p>

            <p class="salario">
                <?php echo htmlspecialchars(MONEDA); ?>

                <?php
                // se presenta el salario con separadores de miles
                echo number_format($salario, 2, ",", ".");
                ?>
            </p>
        </section>

        <!-- se recorren las habilidades almacenadas en el arreglo -->
        <section class="tarjeta">
            <h2>habilidades</h2>

            <ul class="lista-habilidades">
                <?php foreach ($habilidades as $habilidad): ?>

                    <!-- se muestra cada habilidad de la lista -->
                    <li>
                        <?php echo htmlspecialchars($habilidad); ?>
                    </li>

                <?php endforeach; ?>
            </ul>
        </section>

    </main>

    <!-- se muestra el pie de pagina -->
    <footer class="pie">
        <p>practica de variables y tipos de datos</p>
    </footer>

</body>
</html>
