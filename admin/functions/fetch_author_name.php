<?php
function getAuthorName($conn) {
    $query = "SELECT author FROM author";

    $result = mysqli_query($conn, $query);

    $authorName = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $authorName[] = $row['author'];
    }
    return $authorName;
}
?>
