
<?php
// se define el porcentaje de impuesto
const PORCENTAJE_IMPUESTO = 0.13;

// se declara una funcion para calcular el impuesto de un precio
function calcularImpuesto($precio, $porcentaje = PORCENTAJE_IMPUESTO)
{
    return $precio * $porcentaje;
}

// se declara una funcion para calcular el precio final
function calcularPrecioFinal($precio, $porcentaje = PORCENTAJE_IMPUESTO)
{
    return $precio + calcularImpuesto($precio, $porcentaje);
}

// se declara una funcion para formatear un precio en colones
function formatearPrecio($precio)
{
    return "₡" . number_format($precio, 2, ",", ".");
}

// se declara una funcion anonima para aplicar un descuento
$calcularDescuento = function ($precio, $porcentaje = 0.10) {
    return $precio * $porcentaje;
};

// se declara una funcion flecha para calcular un descuento
$obtenerDescuento = fn($precio) => $precio * 0.05;
?>
