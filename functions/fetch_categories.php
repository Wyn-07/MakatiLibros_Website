<?php

// Assuming $pdo is your PDO connection
$sql = "SELECT DISTINCT categories FROM books WHERE categories IS NOT NULL";
$stmt = $pdo->query($sql);

$categories = [];
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cats = explode(',', $row['categories']);
        foreach ($cats as $cat) {
            $cat = trim($cat);
            if (!in_array($cat, $categories)) {
                $categories[] = $cat;
            }
        }
    }
}

?>
