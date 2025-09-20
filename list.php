<?php
// Database connection settings with environment variable overrides
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_DATABASE') ?: 'books_db';
$dbUser = getenv('DB_USERNAME') ?: 'root';
$dbPass = getenv('DB_PASSWORD') ?: '';

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
    $stmt = $pdo->query(
        'SELECT b.title, b.author, b.publication_year, b.image, g.name AS genre
         FROM books b
         INNER JOIN genres g ON b.genre_id = g.id
         ORDER BY b.title'
    );
    $books = $stmt->fetchAll();
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
        $name = htmlspecialchars($book['title']);
        $author = htmlspecialchars($book['author']);
        $image = !empty($book['image']) ? htmlspecialchars($book['image']) : 'https://via.placeholder.com/120x180?text=Book';
        echo '<div class="col">';
        echo '<div class="card h-100 shadow-sm">';
        echo '<img src="' . $image . '" class="card-img-top" alt="' . $name . ' cover" style="height: 240px; object-fit: cover;">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $name . '</h5>';
        echo '<p class="card-text text-muted mb-1">by ' . $author . '</p>';
        if (!empty($book['publication_year'])) {
            echo '<p class="card-text small mb-0">Year: ' . htmlspecialchars((string) $book['publication_year']) . '</p>';
        }
        if (!empty($book['genre'])) {
            echo '<p class="card-text small text-secondary">' . htmlspecialchars($book['genre']) . '</p>';
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
