
<?php

// se define la clase producto
class Producto
{
    // se declaran las propiedades privadas
    private int $id;
    private string $nombre;
    private string $categoria;
    private float $precio;
    private bool $disponible;
    private string $descripcion;

    // se inicializan las propiedades mediante el constructor
    public function __construct(
        int $id,
        string $nombre,
        string $categoria,
        float $precio,
        bool $disponible,
        string $descripcion
    ) {
        // se verifica que el identificador sea valido
        if ($id <= 0) {
            throw new InvalidArgumentException(
                "El identificador debe ser positivo"
            );
        }

        // se verifica que el nombre no este vacio
        if (trim($nombre) === "") {
            throw new InvalidArgumentException(
                "El nombre no puede estar vacio"
            );
        }

        // se verifica que el precio no sea negativo
        if ($precio < 0) {
            throw new InvalidArgumentException(
                "El precio no puede ser negativo"
            );
        }

        // se guardan los datos validados
        $this->id = $id;
        $this->nombre = $nombre;
        $this->categoria = $categoria;
        $this->precio = $precio;
        $this->disponible = $disponible;
        $this->descripcion = $descripcion;
    }

    // se obtiene el identificador del producto
    public function obtenerId(): int
    {
        return $this->id;
    }

    // se obtiene el nombre del producto
    public function obtenerNombre(): string
    {
        return $this->nombre;
    }

    // se obtiene la categoria del producto
    public function obtenerCategoria(): string
    {
        return $this->categoria;
    }

    // se obtiene el precio del producto
    public function obtenerPrecio(): float
    {
        return $this->precio;
    }

    // se obtiene la disponibilidad del producto
    public function estaDisponible(): bool
    {
        return $this->disponible;
    }

    // se obtiene la descripcion del producto
    public function obtenerDescripcion(): string
    {
        return $this->descripcion;
    }

    // se calcula un descuento del diez por ciento
    public function calcularDescuento(): float
    {
        return $this->precio * 0.10;
    }

    // se calcula el precio despues del descuento
    public function obtenerPrecioConDescuento(): float
    {
        return $this->precio - $this->calcularDescuento();
    }
}
