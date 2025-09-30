<?php
// Path to the JSON file
$jsonFile = __DIR__ . '/books.json';

// Check if the file exists
if (!file_exists($jsonFile)) {
	echo "Books data file not found.";
	exit;
}

// Get and decode the JSON data
$jsonData = file_get_contents($jsonFile);
$books = json_decode($jsonData, true);

// Check for JSON errors
if (json_last_error() !== JSON_ERROR_NONE) {
	echo "Error reading books data.";
	exit;
}

// Get book id from GET parameter
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$book = null;
foreach ($books as $b) {
	if ($b['id'] === $id) {
		$book = $b;
		break;
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
	$image = isset($book['image']) && $book['image'] ? htmlspecialchars($book['image']) : 'https://via.placeholder.com/120x180?text=Book';
	$year = isset($book['year']) ? htmlspecialchars($book['year']) : '';
	$genre = isset($book['genre']) ? htmlspecialchars($book['genre']) : '';
	$pages = isset($book['pages']) ? (int)$book['pages'] : '';
	$desc = isset($book['description']) ? htmlspecialchars($book['description']) : '';
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
	if ($pages) echo '<p class="card-text mb-1"><strong>Pages:</strong> ' . $pages . '</p>';
	if ($desc) echo '<p class="card-text mt-3">' . $desc . '</p>';
	echo '<a href="list.php" class="btn btn-primary mt-3">Back to list</a>';
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
