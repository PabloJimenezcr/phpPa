<?php

// se inicia la sesion
session_start();

// se eliminan las variables de sesion
$_SESSION = [];

// se obtiene la configuracion de la cookie de sesion
$configuracionCookie = session_get_cookie_params();

// se elimina la cookie de sesion del navegador
if (ini_get("session.use_cookies")) {
    setcookie(
        session_name(),
        "",
        time() - 42000,
        $configuracionCookie["path"],
        $configuracionCookie["domain"],
        $configuracionCookie["secure"],
        $configuracionCookie["httponly"]
    );
}

// se destruye la sesion
session_destroy();

// se redirige al inicio de sesion
header("Location: login.php");
exit;
