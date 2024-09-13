<?php
function getReadersList($conn) {
    $query = "SELECT firstname, lastname, age, address
              FROM users
              ORDER BY lastname, firstname"; 

    $result = mysqli_query($conn, $query);

    $readersList = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $readersList[] = [
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'age' => $row['age'],
            'address' => $row['address']
        ];
    }
    return $readersList;
}
?>
