<?php
declare(strict_types=1);

require_once __DIR__ . '/Libro.php';

// Database connection settings with environment variable overrides
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_DATABASE') ?: 'llibres';
$dbUser = getenv('DB_USERNAME') ?: 'root';
$dbPass = getenv('DB_PASSWORD') ?: 'secret';

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $dbHost, $dbPort, $dbName);

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo 'Database connection failed. Please check the configuration.';
    exit;
}

try {
    $books = Libro::obtenerTodos($pdo);
} catch (PDOException $e) {
    echo 'Error retrieving books data.';
    exit;
}

// Display the list of books with Bootstrap styling
echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<title>Book List</title>';
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
echo '</head>';
echo '<body class="bg-light">';
echo '<div class="container py-5">';
echo '<h1 class="mb-4 text-center">Book List</h1>';

echo '<div class="mb-3 text-end">';
echo '<a href="list.php" class="btn btn-outline-secondary btn-sm">Reload list</a>';
echo '</div>';

if (empty($books)) {
    echo '<div class="alert alert-info" role="alert">No books found in the catalog.</div>';
} else {
    echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">';
    foreach ($books as $book) {
        $name = htmlspecialchars($book->getTitulo(), ENT_QUOTES, 'UTF-8');
        $author = htmlspecialchars($book->getAutor(), ENT_QUOTES, 'UTF-8');
        $image = htmlspecialchars($book->getImagenConFallback(), ENT_QUOTES, 'UTF-8');
        echo '<div class="col">';
        echo '<div class="card h-100 shadow-sm">';
        echo '<img src="' . $image . '" class="card-img-top" alt="' . $name . ' cover" style="height: 240px; object-fit: cover;">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $name . '</h5>';
        echo '<p class="card-text text-muted mb-1">by ' . $author . '</p>';
        if (!is_null($book->getAnioPublicacion())) {
            echo '<p class="card-text small mb-0">Year: ' . htmlspecialchars((string) $book->getAnioPublicacion(), ENT_QUOTES, 'UTF-8') . '</p>';
        }
        if (!empty($book->getGenero())) {
            echo '<p class="card-text small text-secondary">' . htmlspecialchars($book->getGenero(), ENT_QUOTES, 'UTF-8') . '</p>';
        }
        if (!is_null($book->getId())) {
            echo '<a href="book.php?id=' . urlencode((string) $book->getId()) . '" class="btn btn-sm btn-outline-primary mt-2">View details</a>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
}

echo '</div>';
echo '</body>';
echo '</html>';
?>
