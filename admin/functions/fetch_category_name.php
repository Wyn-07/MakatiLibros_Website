<?php
function getCategoryName($conn) {
    $query = "SELECT category FROM category";

    $result = mysqli_query($conn, $query);

    $categoryName = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categoryName[] = $row['category'];
    }
    return $categoryName;
}
?>
