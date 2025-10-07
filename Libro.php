<?php

declare(strict_types=1);

/**
 * Clase que representa un libro dentro de la aplicación y ofrece
 * métodos de utilidad para consultar el catálogo en la base de datos.
 */
class Libro
{
    private ?int $id;
    private string $titulo;
    private string $autor;
    private ?int $anioPublicacion;
    private ?string $imagen;
    private ?string $genero;
    private ?int $paginas;
    private ?string $descripcion;

    private const IMAGEN_POR_DEFECTO = 'https://via.placeholder.com/120x180?text=Book';

    public function __construct(
        ?int $id,
        string $titulo,
        string $autor,
        ?int $anioPublicacion = null,
        ?string $imagen = null,
        ?string $genero = null,
        ?int $paginas = null,
        ?string $descripcion = null
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->anioPublicacion = $anioPublicacion;
        $this->imagen = $imagen;
        $this->genero = $genero;
        $this->paginas = $paginas;
        $this->descripcion = $descripcion;
    }

    /**
     * Crea una instancia de Libro a partir de los datos devueltos por la BD.
     */
    public static function desdeArray(array $datos): self
    {
        $id = isset($datos['id']) && $datos['id'] !== null ? (int) $datos['id'] : null;
        $titulo = isset($datos['title']) ? (string) $datos['title'] : '';
        $autor = isset($datos['author']) ? (string) $datos['author'] : '';
        $anio = isset($datos['publication_year']) && $datos['publication_year'] !== null && $datos['publication_year'] !== ''
            ? (int) $datos['publication_year']
            : null;
        $imagen = isset($datos['image']) && $datos['image'] !== '' ? (string) $datos['image'] : null;
        $genero = isset($datos['genre']) && $datos['genre'] !== '' ? (string) $datos['genre'] : null;
        $paginas = isset($datos['pages']) && $datos['pages'] !== null && $datos['pages'] !== ''
            ? (int) $datos['pages']
            : null;
        $descripcion = isset($datos['description']) && $datos['description'] !== '' ? (string) $datos['description'] : null;

        return new self($id, $titulo, $autor, $anio, $imagen, $genero, $paginas, $descripcion);
    }

    /**
     * Obtiene todos los libros almacenados en el catálogo.
     *
     * @return self[]
     */
    public static function obtenerTodos(PDO $pdo): array
    {
        $consulta = $pdo->query(
            'SELECT b.id, b.title, b.author, b.publication_year, b.image, g.name AS genre
             FROM books b
             INNER JOIN genres g ON b.genre_id = g.id
             ORDER BY b.title'
        );

        $libros = [];
        while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
            $libros[] = self::desdeArray($fila);
        }

        return $libros;
    }

    /**
     * Recupera un libro concreto por su identificador o null si no existe.
     */
    public static function obtenerPorId(PDO $pdo, int $id): ?self
    {
        $sentencia = $pdo->prepare(
            'SELECT b.id, b.title, b.author, b.publication_year, b.image, b.pages, b.description, g.name AS genre
             FROM books b
             INNER JOIN genres g ON b.genre_id = g.id
             WHERE b.id = :id'
        );

        $sentencia->execute([':id' => $id]);
        $fila = $sentencia->fetch(PDO::FETCH_ASSOC);

        return $fila ? self::desdeArray($fila) : null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getAutor(): string
    {
        return $this->autor;
    }

    public function getAnioPublicacion(): ?int
    {
        return $this->anioPublicacion;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function getPaginas(): ?int
    {
        return $this->paginas;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function getImagenConFallback(): string
    {
        return $this->imagen ?: self::IMAGEN_POR_DEFECTO;
    }
}
