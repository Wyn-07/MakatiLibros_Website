<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authors</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

</head>


<?php

include '../connection.php';
include 'functions/fetch_author_list.php';

$authorList = getAuthorList($conn);
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
                        Author
                    </div>
                </div>

                <div class="container-white">

                    <div class="row row-right">
                        <button class="button-borrow" onclick="openAddModal()">
                            &#43; New
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
                                            <div class="column-title">Author Name</div>
                                            <img id="sort-icon-0" src="../images/sort.png" class="sort">
                                        </div>
                                    </th>
                                    <th>
                                        <div class="column-title">Tools</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($authorList as $author) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($author['author']); ?></td>
                                        <td>
                                            <div class="td-center">
                                                <div class="button-edit" onclick="openEditModal()">
                                                    <img src="../images/edit-white.png" class="image">
                                                </div>
                                                <div class="button-delete" onclick="openDeleteModal()">
                                                    <img src="../images/delete-white.png" class="image">
                                                </div>
                                            </div>
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
                        Add | Author
                    </div>
                    <span class="modal-close" onclick="closeAddModal()">&times;</span>
                </div>

                <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
                    <div class="container-form">

                        <div class="container-input">
                            <label for="name">Author Name</label>
                            <input type="text" name="name" class="input-text" autocomplete="off" required>
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
                        Edit | Author
                    </div>
                    <span class="modal-close" onclick="closeEditModal()">&times;</span>
                </div>

                <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
                    <div class="container-form">

                        <div class="container-input">
                            <label for="name">Author Name</label>
                            <input type="text" name="name" class="input-text" autocomplete="off" required>
                        </div>

                        <div class="row row-right">
                            <button type="submit" name="submit" class="button-submit">Submit</button>
                        </div>
                    </div>
                </form>



            </div>
        </div>









        <div id="deleteModal" class="modal">
            <div class="modal-content">

                <div class="row row-between">
                    <div class="title-26px">
                        Delete | Author
                    </div>
                    <span class="modal-close" onclick="closeDeleteModal()">&times;</span>
                </div>

                <form action="" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
                    <div class="container-form">

                        <div style="text-align: center; margin-bottom: 10px;">
                            Are you sure you want to delete?
                        </div>


                        <div class="row row-center">
                            <button name="cancel" class="button-cancel">No</button>
                            <button type="submit" name="submit" class="button-submit">Yes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
</body>

</html>


<script src="js/sidebar.js"></script>
<script src="js/table-author.js"></script>
<script src="js/add-modal.js"></script>
<script src="js/edit-modal-author.js"></script>
<script src="js/delete-modal-author.js"></script>