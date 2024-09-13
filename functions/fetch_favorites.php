<?php
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

// Get user_id from session
$user_id = $_SESSION['user_id'];

try {
    $sql = "
                SELECT b.book_id, b.title, b.authors, b.image, b.categories, b.copyright, br.status  
                FROM favorites f
                JOIN books b ON f.book_id = b.book_id
                LEFT JOIN borrow br ON b.book_id = br.book_id AND br.user_id = ?
                WHERE f.user_id = ?
            ";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT); // Bind user_id
    $stmt->bindParam(2, $user_id, PDO::PARAM_INT); // Bind user_id for the WHERE clause

    // Execute the statement
    $stmt->execute();

    // Fetch all the results as an associative array
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Error: " . $e->getMessage();
}
