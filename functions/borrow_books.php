<?php
session_start(); // Ensure session is started

try {
    // Database connection
    $pdo = new PDO('mysql:host=localhost;dbname=librodb', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get data from POST request (from the form)
    $bookId = $_POST['book_id'];
    $userId = $_POST['user_id'];
    $status = $_POST['status'];
    $borrowDate = $_POST['borrow_date'];
    $returnDate = $_POST['return_date'];

    // Prepare SQL statement
    $stmt = $pdo->prepare('INSERT INTO borrow (book_id, user_id, status, borrow_date, return_date) VALUES (:book_id, :user_id, :status, :borrow_date, :return_date)');

    // Bind parameters
    $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':borrow_date', $borrowDate, PDO::PARAM_STR);
    $stmt->bindParam(':return_date', $returnDate, PDO::PARAM_STR);

    $stmt->execute();

    // Set success message
    $_SESSION['success_message'] = 'Submitted successfully. Please proceed to the library to collect the book.';
    $_SESSION['success_display'] = 'flex';
    $_SESSION['success_info'] = 'flex';

} catch (PDOException $e) {
    // Handle any errors
    $_SESSION['error_message'] = 'Failed to borrow the book. Error: ' . $e->getMessage();
}

// Redirect back to the referring page or default to userpage.php
$referer = isset($_POST['referer']) ? $_POST['referer'] : '../userpage.php';
header('Location: ' . $referer);
exit;
?>
