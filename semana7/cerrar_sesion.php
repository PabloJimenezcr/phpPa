
<?php

// se inicia o recupera la sesion
session_start();

// se eliminan las variables de la sesion
$_SESSION = [];

// se recuperan las opciones de la cookie de sesion
$opciones = session_get_cookie_params();

// se elimina la cookie de sesion del navegador
if (ini_get("session.use_cookies")) {
    setcookie(
        session_name(),
        "",
        [
            "expires" => time() - 3600,
            "path" => $opciones["path"],
            "domain" => $opciones["domain"],
            "secure" => $opciones["secure"],
            "httponly" => $opciones["httponly"],
            "samesite" => $opciones["samesite"] ?? "Lax"
        ]
    );
}

// se destruye la sesion del servidor
session_destroy();

// se redirige al formulario de acceso
header("Location: index.php");
exit;
