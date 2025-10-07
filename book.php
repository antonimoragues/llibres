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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$book = null;

if ($id > 0) {
    try {
        $book = Libro::obtenerPorId($pdo, $id);
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

if ($book) {
    $name = htmlspecialchars($book->getTitulo(), ENT_QUOTES, 'UTF-8');
    $author = htmlspecialchars($book->getAutor(), ENT_QUOTES, 'UTF-8');
    $image = htmlspecialchars($book->getImagenConFallback(), ENT_QUOTES, 'UTF-8');
    $year = $book->getAnioPublicacion();
    $genre = $book->getGenero();
    $pages = $book->getPaginas();
    $description = $book->getDescripcion();

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
    if (!is_null($year)) {
        echo '<p class="card-text mb-1"><strong>Year:</strong> ' . htmlspecialchars((string) $year, ENT_QUOTES, 'UTF-8') . '</p>';
    }
    if (!empty($genre)) {
        echo '<p class="card-text mb-1"><strong>Genre:</strong> ' . htmlspecialchars($genre, ENT_QUOTES, 'UTF-8') . '</p>';
    }
    if (!is_null($pages) && $pages > 0) {
        echo '<p class="card-text mb-1"><strong>Pages:</strong> ' . htmlspecialchars((string) $pages, ENT_QUOTES, 'UTF-8') . '</p>';
    }
    if (!empty($description)) {
        echo '<p class="card-text mt-3">' . nl2br(htmlspecialchars($description, ENT_QUOTES, 'UTF-8')) . '</p>';
    }
    echo '<a href="list.php" class="btn btn-primary mt-3">Back to list</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
} else {
    echo '<div class="alert alert-danger">Book not found.</div>';
    echo '<a href="list.php" class="btn btn-primary">Back to list</a>';
}

echo '</div>';
echo '</body>';
echo '</html>';
?>
