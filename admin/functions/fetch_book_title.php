<?php
function getBookTitles($conn) {
    $query = "SELECT title FROM books ORDER BY title ASC";

    $result = mysqli_query($conn, $query);

    $bookTitles = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bookTitles[] = $row['title'];
    }
    return $bookTitles;
}
?>
