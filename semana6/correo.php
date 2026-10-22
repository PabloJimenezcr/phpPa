
<?php

// se carga la biblioteca instalada mediante composer
require_once __DIR__ . "/vendor/autoload.php";

// se importan las clases necesarias para enviar correos
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// se declara una funcion para enviar la confirmacion
function enviarConfirmacion($nombre, $correo)
{
    // se crea una instancia de phpmailer
    $mail = new PHPMailer(true);

    try {
        // se configura el envio mediante smtp
        $mail->isSMTP();

        // se indica el servidor smtp del proveedor
        $mail->Host = "smtp.gmail.com";

        // se activa la autenticacion smtp
        $mail->SMTPAuth = true;

        // se indica la cuenta autorizada para enviar correos
        $mail->Username = "suCorreo@gmail.com";

        // se indica la clave smtp autorizada para la cuenta
        // se debe generar la clave por este link: https://myaccount.google.com/security
        // despues en la barra colocar "contraseñas de aplicacion" y ahi generarla
        $mail->Password = "wewewewewew";

        // se utiliza cifrado tls
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        // se configura el puerto smtp
        $mail->Port = 587;

        // se define la codificacion del mensaje
        $mail->CharSet = "UTF-8";

        // se define la cuenta que envia el mensaje
        $mail->setFrom("cuenta@ejemplo.com", "Registro de cursos");

        // se define el destinatario del mensaje
        $mail->addAddress($correo, $nombre);

        // se define el formato html del mensaje
        $mail->isHTML(true);

        // se define el asunto del correo
        $mail->Subject = "Confirmacion de registro";

        // se construye el mensaje html de confirmacion
        $mail->Body = "
            <h1>Registro recibido</h1>
            <p>Hola " . htmlspecialchars($nombre, ENT_QUOTES, "UTF-8") . "</p>
            <p>El formulario se valido correctamente y el registro fue recibido</p>
            <p>Gracias por completar el formulario</p>
        ";

        // se define una version de texto para el mensaje
        $mail->AltBody = "Hola " . $nombre . ", el formulario se valido correctamente y el registro fue recibido";

        // se envia el correo
        $mail->send();

        // se indica que el envio finalizo correctamente
        return true;

    } catch (Exception $e) {
        // se registra el error sin mostrar credenciales al usuario
        error_log("Error al enviar el correo: " . $mail->ErrorInfo);

        // se indica que el envio fallo
        return false;
    }
}
?>
