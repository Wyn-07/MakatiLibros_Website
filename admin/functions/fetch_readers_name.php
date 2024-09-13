<?php
function getReadersNames($conn) {
    $query = "SELECT firstname, lastname FROM users ORDER BY lastname, firstname";

    $result = mysqli_query($conn, $query);

    $readersNames = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $readersNames[] = $row['firstname'] . ' ' . $row['lastname'];
    }
    return $readersNames;
}
?>
