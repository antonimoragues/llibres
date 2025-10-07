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

// Get book id from GET parameter
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$book = null;

require_once __DIR__ . '/src/Book.php';

if ($id > 0) {
        try {
                $book = Book::findById($pdo, $id);
        } catch (PDOException $e) {
                echo 'Error retrieving book data.';
                exit;
        }
}

echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<title>Book Details</title>';
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
echo '</head>';
echo '<body class="bg-light">';
echo '<div class="container-fluid py-5">';
if ($book instanceof Book) {
        $name = htmlspecialchars($book->getTitle());
        $author = htmlspecialchars($book->getAuthor());
        $image = htmlspecialchars($book->getImageUrl());
        echo '<div class="row justify-content-center">';
        echo '<div class="col-12 col-md-10 col-lg-8">';
        echo '<div class="card shadow px-4">';
        echo '<div class="row g-0">';
        echo '<div class="col-md-5">';
        echo '<img src="' . $image . '" class="img-fluid rounded-start w-100" alt="' . $name . ' cover" style="height:100%;object-fit:cover;">';
        echo '</div>';
        echo '<div class="col-md-7">';
        echo '<div class="card-body">';
        echo '<h3 class="card-title">' . $name . '</h3>';
        echo '<p class="card-text mb-1"><strong>Author:</strong> ' . $author . '</p>';
        if ($book->hasPublicationYear()) echo '<p class="card-text mb-1"><strong>Year:</strong> ' . htmlspecialchars((string) $book->getPublicationYear()) . '</p>';
        if ($book->hasGenre()) echo '<p class="card-text mb-1"><strong>Genre:</strong> ' . htmlspecialchars((string) $book->getGenre()) . '</p>';
        if ($book->hasPages()) echo '<p class="card-text mb-1"><strong>Pages:</strong> ' . htmlspecialchars((string) $book->getPages()) . '</p>';
        if ($book->hasDescription()) echo '<p class="card-text mt-3">' . htmlspecialchars((string) $book->getDescription()) . '</p>';
	echo '<a href="index.php" class="btn btn-primary mt-3">Back to list</a>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
} else {
	echo '<div class="alert alert-danger">Book not found.</div>';
	echo '<a href="index.php" class="btn btn-primary">Back to list</a>';
}
echo '</div>';
echo '</body>';
echo '</html>';
?>
