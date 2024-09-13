<?php
function getCategoryList($conn) {
    $query = "SELECT DISTINCT bk.categories
              FROM books bk
              ORDER BY bk.categories ASC";

    $result = mysqli_query($conn, $query);

    $categoryList = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categoryList[] = [
            'category' => $row['categories']
        ];
    }
    return $categoryList;
}
?>
