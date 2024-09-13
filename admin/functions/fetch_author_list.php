<?php
function getAuthorList($conn) {
    $query = "SELECT DISTINCT bk.authors
              FROM books bk
              ORDER BY bk.authors ASC";

    $result = mysqli_query($conn, $query);

    $authorList = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $authorList[] = [
            'author' => $row['authors']
        ];
    }
    return $authorList;
}
?>
