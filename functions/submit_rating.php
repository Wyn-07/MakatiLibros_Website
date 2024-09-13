<?php
session_start(); // Start the session

include '../../connection.php'; // Assuming this file sets up the PDO connection as $pdo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'];
    $user_id = $_POST['user_id'];
    $rating = $_POST['rate'];

    // Validate inputs
    if (!empty($book_id) && !empty($user_id) && !empty($rating)) {
        try {
            // Check if the user has already rated the book
            $checkQuery = "SELECT rating_id FROM ratings WHERE book_id = :book_id AND user_id = :user_id";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
            $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                // Update the rating
                $query = "UPDATE ratings SET ratings = :ratings WHERE book_id = :book_id AND user_id = :user_id";
            } else {
                // Insert a new rating
                $query = "INSERT INTO ratings (book_id, user_id, ratings) VALUES (:book_id, :user_id, :ratings)";
            }

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':ratings', $rating, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                // Store the message and display status in session
                $_SESSION['success_message'] = "Rated successfully.";
                $_SESSION['success_display'] = 'flex';
                header("Location: ../userpage.php");
                exit;
            } else {
                echo "Error executing query.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid input.";
    }
}
