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
echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">';
foreach ($books as $book) {
    $name = htmlspecialchars($book['title']);
    $author = htmlspecialchars($book['author']);
    $image = isset($book['image']) && $book['image'] ? htmlspecialchars($book['image']) : 'https://via.placeholder.com/120x180?text=Book';
    $id = isset($book['id']) ? (int)$book['id'] : 0;
    echo '<div class="col">';
    echo '<div class="card h-100 shadow-sm">';
    echo '<a href="book.php?id=' . $id . '" style="text-decoration:none;color:inherit">';
    echo '<img src="' . $image . '" class="card-img-top" alt="' . $name . ' cover" style="height: 240px; object-fit: cover;">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . $name . '</h5>';
    echo '<p class="card-text text-muted mb-1">by ' . $author . '</p>';
    if (isset($book['year'])) echo '<p class="card-text small mb-0">Year: ' . htmlspecialchars($book['year']) . '</p>';
    if (isset($book['genre'])) echo '<p class="card-text small text-secondary">' . htmlspecialchars($book['genre']) . '</p>';
    echo '</div>';
    echo '</a>';
    echo '</div>';
    echo '</div>';
}
echo '</div>';
echo '</div>';
echo '</body>';
echo '</html>';
?>