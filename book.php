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

if ($id > 0) {
        try {
                $stmt = $pdo->prepare(
                        'SELECT b.title, b.author, b.publication_year, b.image, b.pages, b.description, g.name AS genre
                         FROM books b
                         INNER JOIN genres g ON b.genre_id = g.id
                         WHERE b.id = :id'
                );
                $stmt->execute([':id' => $id]);
                $book = $stmt->fetch();
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
        $name = htmlspecialchars($book['title']);
        $author = htmlspecialchars($book['author']);
        $image = !empty($book['image']) ? htmlspecialchars($book['image']) : 'https://via.placeholder.com/120x180?text=Book';
        $year = !empty($book['publication_year']) ? htmlspecialchars((string) $book['publication_year']) : '';
        $genre = !empty($book['genre']) ? htmlspecialchars($book['genre']) : '';
        $pages = isset($book['pages']) ? (int) $book['pages'] : null;
        $desc = !empty($book['description']) ? htmlspecialchars($book['description']) : '';
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
	if ($year) echo '<p class="card-text mb-1"><strong>Year:</strong> ' . $year . '</p>';
	if ($genre) echo '<p class="card-text mb-1"><strong>Genre:</strong> ' . $genre . '</p>';
        if (!is_null($pages) && $pages > 0) echo '<p class="card-text mb-1"><strong>Pages:</strong> ' . $pages . '</p>';
	if ($desc) echo '<p class="card-text mt-3">' . $desc . '</p>';
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
