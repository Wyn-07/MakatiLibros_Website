<?php
function getBookList($conn) {
    $query = "SELECT bk.copyright, bk.categories, bk.title, bk.authors
              FROM books bk
              ORDER BY bk.copyright DESC";

    $result = mysqli_query($conn, $query);

    $bookList = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bookList[] = [
            'copyright' => $row['copyright'],
            'category' => $row['categories'],
            'title' => $row['title'],
            'author' => $row['authors']
        ];
    }
    return $bookList;
}
?>
