<?php
session_start(); // Ensure session is started

try {
    // Database connection
    $pdo = new PDO('mysql:host=localhost;dbname=librodb', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get data from POST request (from the form)
    $bookId = $_POST['book_id'];
    $userId = $_POST['user_id'];
    $date = $_POST['date'];

    // Prepare SQL statement
    $stmt = $pdo->prepare('INSERT INTO favorites (book_id, user_id, date) VALUES (:book_id, :user_id, :date)');

    // Bind parameters
    $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':date', $borrowDate, PDO::PARAM_STR);

    $stmt->execute();

    // Redirect with success message
    $_SESSION['success_message'] = 'Added successfully';
    $_SESSION['success_display'] = 'flex';
    header('Location: ../userpage.php');
    exit;

} catch (PDOException $e) {
    // Handle any errors
    $_SESSION['error_message'] = 'Failed to add the book. Error: ' . $e->getMessage();
    header('Location: ../userpage.php');
    exit;
}
?>
