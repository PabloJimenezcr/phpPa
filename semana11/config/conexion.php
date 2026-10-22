<?php

// se definen los datos de conexion
$host = "localhost";
$baseDatos = "semana11_crud";
$usuario = "root";
$contrasena = "";

// se construye la cadena de conexion
$dsn = "mysql:host=$host;dbname=$baseDatos;charset=utf8mb4";

try {
    // se establece la conexion
    $conexion = new PDO($dsn, $usuario, $contrasena);

    // se configura el manejo de errores
    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // se configuran los resultados como arreglos asociativos
    $conexion->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );
} catch (PDOException $error) {
    // se registra el detalle tecnico
    error_log($error->getMessage());

    // se detiene la aplicacion si falla la conexion
    exit("No fue posible conectar con la base de datos");
}
