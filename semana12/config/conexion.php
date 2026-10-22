<?php

// se configura el acceso a la base de datos
$servidor = "localhost";
$baseDatos = "semana12_seguridad";
$usuario = "root";
$contrasena = "";

try {
    // se crea la conexion con mysql
    $conexion = new PDO(
        "mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4",
        $usuario,
        $contrasena
    );

    // se configuran los errores mediante excepciones
    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // se configura el resultado de las consultas como arreglos asociativos
    $conexion->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );
} catch (PDOException $error) {
    // se registra el error sin exponer detalles tecnicos al usuario
    error_log($error->getMessage());

    // se detiene la ejecucion con un mensaje general
    exit("No fue posible conectar con el sistema");
}
