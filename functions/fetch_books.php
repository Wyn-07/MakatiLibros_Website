<?php
// Query to fetch book categories, titles, images, authors, book_id, average ratings, and borrow status
$sql = "SELECT b.categories, b.title, b.image, b.book_id, b.authors, 
               IFNULL(ROUND(AVG(r.ratings), 2), 0) as avg_rating,
               br.status
        FROM books b
        LEFT JOIN ratings r ON b.book_id = r.book_id
        LEFT JOIN borrow br ON b.book_id = br.book_id
        GROUP BY b.book_id, b.categories, b.title, b.image, b.authors, br.status";

$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all results
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$books = [];
foreach ($result as $row) {
    // Skip rows where category is empty
    if (!empty($row['categories'])) {
        // Add title, image, book_id, authors, avg_rating, and borrow_status to the respective category
        $books[$row['categories']][] = [
            'book_id' => $row['book_id'],
            'title' => $row['title'],
            'image' => $row['image'],
            'authors' => $row['authors'],
            'categories' => $row['categories'],
            'avg_rating' => number_format($row['avg_rating'], 1), 
            'status' => $row['status'] 
        ];
    }
}

// Function to remove duplicates based on 'title'
function removeDuplicates($array)
{
    $unique = [];
    $titles = [];

    foreach ($array as $item) {
        if (!in_array($item['title'], $titles)) {
            $unique[] = $item;
            $titles[] = $item['title'];
        }
    }

    return $unique;
}

// Remove duplicate titles within each category
foreach ($books as $category => $bookDetails) {
    $books[$category] = removeDuplicates($bookDetails);
}

// Remove categories with no books
$books = array_filter($books, function ($bookDetails) {
    return !empty($bookDetails);
});

?>
