<?php

class Book
{
    private const DEFAULT_IMAGE = 'https://via.placeholder.com/120x180?text=Book';

    private ?int $id;
    private string $title;
    private string $author;
    private ?int $publicationYear;
    private ?string $image;
    private ?string $genre;
    private ?int $pages;
    private ?string $description;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->id = isset($data['id']) ? (int) $data['id'] : null;
        $this->title = $data['title'] ?? '';
        $this->author = $data['author'] ?? '';
        $this->publicationYear = isset($data['publication_year']) ? (int) $data['publication_year'] : null;
        $this->image = $data['image'] ?? null;
        $this->genre = $data['genre'] ?? null;
        $this->pages = isset($data['pages']) ? (int) $data['pages'] : null;
        $this->description = $data['description'] ?? null;
    }

    public static function fetchAll(PDO $pdo): array
    {
        $stmt = $pdo->query(
            'SELECT b.id, b.title, b.author, b.publication_year, b.image, g.name AS genre
             FROM books b
             INNER JOIN genres g ON b.genre_id = g.id
             ORDER BY b.title'
        );

        $rows = $stmt->fetchAll();

        return array_map(static fn(array $row) => new self($row), $rows);
    }

    public static function findById(PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare(
            'SELECT b.id, b.title, b.author, b.publication_year, b.image, b.pages, b.description, g.name AS genre
             FROM books b
             INNER JOIN genres g ON b.genre_id = g.id
             WHERE b.id = :id'
        );

        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new self($row);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function hasPublicationYear(): bool
    {
        return $this->publicationYear !== null && $this->publicationYear > 0;
    }

    public function getPublicationYear(): ?int
    {
        return $this->publicationYear;
    }

    public function getImageUrl(): string
    {
        return !empty($this->image) ? $this->image : self::DEFAULT_IMAGE;
    }

    public function hasGenre(): bool
    {
        return !empty($this->genre);
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function hasPages(): bool
    {
        return $this->pages !== null && $this->pages > 0;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function hasDescription(): bool
    {
        return !empty($this->description);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
