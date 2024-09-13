<?php
function getReturnedBooks($conn) {
    $query = "SELECT b.return_date, u.firstname, u.lastname, bk.title
              FROM borrow b 
              JOIN users u ON b.user_id = u.user_id 
              JOIN books bk ON b.book_id = bk.book_id
              WHERE b.return_date IS NOT NULL AND b.return_date != ''
              ORDER BY STR_TO_DATE(b.return_date, '%M %d, %Y') DESC";

    $result = mysqli_query($conn, $query);

    $returnedBooks = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $returnedBooks[] = [
            'return_date' => $row['return_date'],
            'name' => $row['firstname'] . ' ' . $row['lastname'],
            'title' => $row['title']
        ];
    }
    return $returnedBooks;
}
?>
