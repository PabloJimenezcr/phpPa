<?php
// se inicia la sesion para conservar datos temporalmente
session_start();

// se prepara el arreglo de errores
$errores = [];

// se prepara el mensaje de resultado
$mensaje = "";

// se recuperan los datos guardados temporalmente
$datos = $_SESSION["datos_formulario"] ?? [];

// se define la lista de intereses permitidos
$interesesPermitidos = ["PHP", "JavaScript", "Bases de datos"];

// se define la lista de paises permitidos
$paisesPermitidos = ["cr", "pa", "gt"];

// se define la lista de modalidades permitidas
$modalidadesPermitidas = ["presencial", "virtual"];

// se comprueba si el formulario fue enviado mediante post
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // se obtiene el nombre y se eliminan espacios externos
    $nombre = trim($_POST["nombre"] ?? "");

    // se obtiene el correo y se eliminan espacios externos
    $correo = trim($_POST["correo"] ?? "");

    // se obtiene el telefono y se eliminan espacios externos
    $telefono = trim($_POST["telefono"] ?? "");

    // se obtiene la modalidad seleccionada
    $modalidad = $_POST["modalidad"] ?? "";

    // se obtiene el pais seleccionado
    $pais = $_POST["pais"] ?? "";

    // se comprueba si se recibio un arreglo de intereses
    $interesesRecibidos = $_POST["intereses"] ?? [];

    // se comprueba si se aceptaron las condiciones
    $aceptaCondiciones = isset($_POST["condiciones"]);

    // se validan los tipos de los datos recibidos
    if (
        !is_string($nombre) || !is_string($correo) ||
        !is_string($telefono) || !is_string($modalidad) ||
        !is_string($pais)
    ) {

        $errores[] = "Los datos recibidos no tienen un formato valido";
    } else {

        // se valida que el nombre sea obligatorio
        if ($nombre === "") {
            $errores[] = "El nombre completo es obligatorio";
        } elseif (strlen($nombre) > 100) {
            $errores[] = "El nombre no puede superar los cien caracteres";
        }

        // se valida el formato del correo electronico
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Debe ingresar un correo electronico valido";
        }

        // se valida que el telefono contenga entre siete y veinte caracteres permitidos
        if (!preg_match('/^[0-9+() -]{7,20}$/', $telefono)) {
            $errores[] = "Debe ingresar un telefono valido";
        }

        // se valida que la modalidad pertenezca a las opciones permitidas
        if (!in_array($modalidad, $modalidadesPermitidas, true)) {
            $errores[] = "Debe seleccionar una modalidad valida";
        }

        // se valida que el pais pertenezca a las opciones permitidas
        if (!in_array($pais, $paisesPermitidos, true)) {
            $errores[] = "Debe seleccionar un pais valido";
        }

        // se valida que los intereses recibidos sean un arreglo
        if (!is_array($interesesRecibidos)) {
            $errores[] = "La seleccion de intereses no es valida";
            $intereses = [];
        } else {
            // se conservan solamente los intereses permitidos
            $intereses = array_values(
                array_intersect($interesesRecibidos, $interesesPermitidos)
            );
        }

        // se valida la aceptacion de las condiciones
        if (!$aceptaCondiciones) {
            $errores[] = "Debe aceptar las condiciones para continuar";
        }
    }

    // se comprueba si se envio una fotografia
    $fotografia = $_FILES["fotografia"] ?? null;

    if (
        $fotografia !== null &&
        $fotografia["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        // se valida que la carga haya finalizado correctamente
        if ($fotografia["error"] !== UPLOAD_ERR_OK) {
            $errores[] = "No se pudo recibir la fotografia";

            // se valida el tamano maximo de dos megabytes
        } elseif ($fotografia["size"] > 2 * 1024 * 1024) {
            $errores[] = "La fotografia no puede superar dos megabytes";
        } else {
            // se comprueba el tipo real del archivo
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $tipoArchivo = $finfo->file($fotografia["tmp_name"]);

            // se define una lista de tipos de imagen permitidos
            $tiposPermitidos = [
                "image/jpeg" => "jpg",
                "image/png" => "png",
                "image/webp" => "webp"
            ];

            // se rechazan los archivos que no sean imagenes permitidas
            if (!isset($tiposPermitidos[$tipoArchivo])) {
                $errores[] = "La fotografia debe ser JPG, PNG o WEBP";
            }
        }
    }

    // se conservan temporalmente los datos para volver a mostrarlos
    $_SESSION["datos_formulario"] = [
        "nombre" => is_string($nombre) ? $nombre : "",
        "correo" => is_string($correo) ? $correo : "",
        "telefono" => is_string($telefono) ? $telefono : "",
        "modalidad" => is_string($modalidad) ? $modalidad : "",
        "pais" => is_string($pais) ? $pais : "",
        "intereses" => $intereses ?? [],
        "condiciones" => $aceptaCondiciones
    ];

    // se procesa el registro solamente si no existen errores
    if (empty($errores)) {

        // se prepara la ruta para guardar la fotografia
        $rutaFotografia = null;

        // se guarda la fotografia si el usuario envio una
        if (
            $fotografia !== null &&
            $fotografia["error"] === UPLOAD_ERR_OK
        ) {

            // se crea la carpeta de archivos si no existe
            $directorio = __DIR__ . "/uploads";

            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }

            // se genera un nombre aleatorio para el archivo
            $nombreArchivo = bin2hex(random_bytes(16)) .
                "." . $tiposPermitidos[$tipoArchivo];

            // se define la ruta final del archivo
            $destino = $directorio . "/" . $nombreArchivo;

            // se mueve el archivo a la carpeta de destino
            if (move_uploaded_file($fotografia["tmp_name"], $destino)) {
                $rutaFotografia = $nombreArchivo;
            } else {
                $errores[] = "No se pudo guardar la fotografia";
            }
        }

        // se envia el correo solo si no existen errores
        if (empty($errores)) {

            // se carga la funcion que envia el correo
            require_once __DIR__ . "/correo.php";

            // se envia el correo de confirmacion
            if (enviarConfirmacion($nombre, $correo)) {

                // se prepara el mensaje de exito
                $mensaje = "El registro fue validado y se envio el correo de confirmacion";

                // se limpia la informacion temporal del formulario
                unset($_SESSION["datos_formulario"]);

                // se reinician los datos mostrados en el formulario
                $datos = [];
            } else {

                // se informa que el correo no pudo enviarse
                $errores[] = "Los datos son validos, pero no se pudo enviar el correo. Verifique la configuracion SMTP";
            }
        }
    }

    // se recuperan los datos para conservarlos si hubo errores
    $datos = $_SESSION["datos_formulario"] ?? [];
}

// se define una funcion para mostrar valores de forma segura
function valorCampo($datos, $campo)
{
    return htmlspecialchars(
        (string) ($datos[$campo] ?? ""),
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
    <title>Registro de estudiantes</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <main class="contenedor">

        <header class="encabezado">
            <p class="etiqueta">CURSOS DE FORMACION</p>
            <h1>Registro de estudiantes</h1>
            <p>Complete el formulario para solicitar su registro</p>
        </header>

        <section class="formulario-contenedor">

            <?php if ($mensaje !== ""): ?>
                <div class="mensaje exito" role="status">
                    <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errores)): ?>
                <div class="mensaje error" role="alert">
                    <strong>Revise los siguientes datos:</strong>

                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li>
                                <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">

                <div class="campo">
                    <label for="nombre">Nombre completo *</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="100"
                        value="<?= valorCampo($datos, "nombre") ?>"
                        required>
                </div>

                <div class="campo">
                    <label for="correo">Correo electronico *</label>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        value="<?= valorCampo($datos, "correo") ?>"
                        required>
                </div>

                <div class="campo">
                    <label for="telefono">Telefono *</label>
                    <input
                        type="tel"
                        id="telefono"
                        name="telefono"
                        value="<?= valorCampo($datos, "telefono") ?>"
                        required>
                </div>

                <fieldset class="grupo">
                    <legend>Modalidad preferida *</legend>

                    <label class="opcion">
                        <input
                            type="radio"
                            name="modalidad"
                            value="presencial"
                            <?= ($datos["modalidad"] ?? "") === "presencial" ? "checked" : "" ?>
                            required>
                        Presencial
                    </label>

                    <label class="opcion">
                        <input
                            type="radio"
                            name="modalidad"
                            value="virtual"
                            <?= ($datos["modalidad"] ?? "") === "virtual" ? "checked" : "" ?>
                            required>
                        Virtual
                    </label>
                </fieldset>

                <div class="campo">
                    <label for="pais">Pais de residencia *</label>

                    <select id="pais" name="pais" required>
                        <option value="">Seleccione un pais</option>

                        <option
                            value="cr"
                            <?= ($datos["pais"] ?? "") === "cr" ? "selected" : "" ?>>
                            Costa Rica
                        </option>

                        <option
                            value="pa"
                            <?= ($datos["pais"] ?? "") === "pa" ? "selected" : "" ?>>
                            Panama
                        </option>

                        <option
                            value="gt"
                            <?= ($datos["pais"] ?? "") === "gt" ? "selected" : "" ?>>
                            Guatemala
                        </option>
                    </select>
                </div>

                <fieldset class="grupo">
                    <legend>Areas de interes</legend>

                    <?php foreach ($interesesPermitidos as $interes): ?>
                        <label class="opcion">
                            <input
                                type="checkbox"
                                name="intereses[]"
                                value="<?= htmlspecialchars($interes, ENT_QUOTES, "UTF-8") ?>"
                                <?= in_array($interes, $datos["intereses"] ?? [], true) ? "checked" : "" ?>>
                            <?= htmlspecialchars($interes, ENT_QUOTES, "UTF-8") ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

                <div class="campo">
                    <label for="fotografia">Fotografia de perfil opcional</label>
                    <input
                        type="file"
                        id="fotografia"
                        name="fotografia"
                        accept=".jpg,.jpeg,.png,.webp">
                    <small>Formatos JPG, PNG o WEBP. Maximo 2 MB.</small>
                </div>

                <label class="opcion condiciones">
                    <input
                        type="checkbox"
                        name="condiciones"
                        value="1"
                        <?= !empty($datos["condiciones"]) ? "checked" : "" ?>
                        required>
                    Acepto las condiciones del registro *
                </label>

                <button type="submit">Enviar registro</button>

                <p class="nota">Los campos marcados con * son obligatorios.</p>

            </form>

        </section>

    </main>

</body>

</html>