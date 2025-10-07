<?php
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

require_once __DIR__ . '/src/Book.php';

try {
    $books = Book::fetchAll($pdo);
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

if (empty($books)) {
    echo '<div class="alert alert-info" role="alert">No books found in the catalog.</div>';
} else {
    echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">';
    foreach ($books as $book) {
        $name = htmlspecialchars($book->getTitle());
        $author = htmlspecialchars($book->getAuthor());
        $image = htmlspecialchars($book->getImageUrl());
        $id = $book->getId();
        $detailUrl = $id !== null ? 'book.php?id=' . urlencode((string) $id) : null;
        echo '<div class="col">';
        echo '<div class="card h-100 shadow-sm position-relative">';
        echo '<img src="' . $image . '" class="card-img-top" alt="' . $name . ' cover" style="height: 240px; object-fit: cover;">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $name . '</h5>';
        echo '<p class="card-text text-muted mb-1">by ' . $author . '</p>';
        if ($book->hasPublicationYear()) {
            echo '<p class="card-text small mb-0">Year: ' . htmlspecialchars((string) $book->getPublicationYear()) . '</p>';
        }
        if ($book->hasGenre()) {
            echo '<p class="card-text small text-secondary">' . htmlspecialchars((string) $book->getGenre()) . '</p>';
        }
        if ($detailUrl !== null) {
            echo '<a href="' . $detailUrl . '" class="stretched-link" aria-label="View details for ' . $name . '"></a>';
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
