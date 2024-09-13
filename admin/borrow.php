<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>

<?php

include '../connection.php';

include 'functions/fetch_borrowed_books.php';
$borrowedBooks = getBorrowedBooks($conn);


include 'functions/fetch_book_title.php';
$bookTitles = getBookTitles($conn);


include 'functions/fetch_readers_name.php';
$readersNames = getReadersNames($conn);

?>

<body>
    <div class="wrapper">
        <div class="container-top">

            <div class="row row-between">

                <div class="row-auto">
                    <div class="container-round logo">
                        <img src="../images/makati-logo.png" class="image">
                    </div>
                    MakatiLibros
                </div>


                <div class="row-auto container-profile" onclick="openEditModal()">
                    <div class="container-round profile">
                        <img src="../images/sample-profile.jpg" class="image">
                    </div>
                    Wyn Bacolod
                </div>

            </div>

        </div>

        <div class="container-content">

            <div class="sidebar">

                <?php include 'sidebar.php'; ?>

            </div>


            <div class="body">
                <div class="row">
                    <div class="title-26px">
                        Borrow Books
                    </div>
                </div>

                <div class="container-white">

                    <div class="row row-right">
                        <button class="button-borrow" onclick="openAddModal()">
                            &#43; Borrow
                        </button>
                    </div>

                    <div class="row row-between">

                        <div>
                            <label for="search">Search: </label>
                            <input class="table-search" type="text" id="search" onkeyup="searchTable()">
                        </div>

                        <div>
                            <label for="entries">Show </label>
                            <select class="table-select" id="entries" onchange="changeEntries()">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                            <label for="entries"> entries</label>
                        </div>

                    </div>

                    <div class="row">

                        <table id="table">
                            <thead>
                                <tr>
                                    <th onclick="sortTable(0)">
                                        <div class="row row-between">
                                            <div class="column-title">Date</div>
                                            <img id="sort-icon-0" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(1)">
                                        <div class="row row-between">
                                            <div class="column-title">Name</div>
                                            <img id="sort-icon-1" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(2)">
                                        <div class="row row-between">
                                            <div class="column-title">Book Title</div>
                                            <img id="sort-icon-2" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th onclick="sortTable(3)">
                                        <div class="row row-between">
                                            <div class="column-title">Status</div>
                                            <img id="sort-icon-3" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($borrowedBooks as $book) { ?>
                                    <tr>
                                        <td><?php echo $book['borrow_date']; ?></td>
                                        <td><?php echo $book['name']; ?></td>
                                        <td><?php echo $book['title']; ?></td>
                                        <td>
                                            <center>
                                                <div class="status <?php echo strtolower($book['status']); ?>">
                                                    <?php echo $book['status']; ?>
                                                </div>
                                            </center>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>



                    </div>

                    <div class="row row-between">
                        <div class="entry-info" id="entry-info"></div>
                        <div class="pagination" id="pagination"></div>
                    </div>

                </div>

            </div>

        </div>














        <div id="addModal" class="modal">
            <div class="modal-content">

                <div class="row row-between">
                    <div class="title-26px">
                        Add | Borrow
                    </div>
                    <span class="modal-close" onclick="closeAddModal()">&times;</span>
                </div>

                <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
                    <div class="container-form">

                        <div class="container-input">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="input-text" id="nameInput" autocomplete="off" required>
                        </div>

                        <div class="container-input">
                            <label for="title">Book Title</label>
                            <input type="text" id="titleInput" name="title" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="row row-right">
                            <button type="submit" name="submit" class="button-submit">Submit</button>
                        </div>
                    </div>
                </form>



            </div>
        </div>






        <div id="editModal" class="modal">
            <div class="modal-content">

                <div class="row row-between">
                    <div class="title-26px">
                        Edit | Profile
                    </div>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>

                <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
                    <div class="container-form">

                        <div class="container-input">
                            <label for="image">Image</label>
                            <input type="file" name="image" class="input-text" autocomplete="off" required>

                            <div class="profile-image">
                                <img src="" alt="">
                            </div>
                        </div>


                        <div class="container-input">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="container-input">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="input-text" min="1" autocomplete="off" required>
                        </div>

                        <div class="container-input">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="input-text" min="1" autocomplete="off" required>
                        </div>

                        <div class="row row-right">
                            <button type="submit" name="submit" class="button-submit">Submit</button>
                        </div>
                    </div>
                </form>



            </div>
        </div>

    </div>
</body>

</html>


<script src="js/sidebar.js"></script>
<script src="js/table-borrow.js"></script>
<script src="js/add-modal.js"></script>

<script>
    const readersNames = <?php echo json_encode($readersNames); ?>;
</script>
<script src="js/autocomplete-readers.js"></script>

<script>
    const bookTitles = <?php echo json_encode($bookTitles); ?>;
</script>
<script src="js/autocomplete-book-title.js"></script>