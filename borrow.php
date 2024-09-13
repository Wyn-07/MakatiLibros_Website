<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books</title>

    <link rel="stylesheet" href="style.css">

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>

<?php
session_start();
?>
<?php include '../connection.php'; ?>

<body>
    <div class="wrapper">

        <div class="container-top">
            <?php include 'navbar.php'; ?>
        </div>

        <div id="overlay" class="overlay"></div>


        <div class="row-body">

            <div class="container-sidebar" id="sidebar">

                <?php include 'sidebar.php'; ?>

            </div>


            <div class="container-content">

                <div class="row row-between">

                    <div class="contents-title">
                        Borrowed Books
                    </div>

                    <div class="container-search row">
                        <input type="text" id="search" class="search" placeholder="Search by title or author">

                        <div class="container-search-image">
                            <div class="search-image">
                                <img src="../images/search-black.png" class="image">
                            </div>
                        </div>
                    </div>


                </div>


                <div class="row">

                    <div class="container-filter">

                        <?php include 'functions/fetch_categories.php'; ?>

                        <div class="row auto">
                            <div class="filter-content-image">
                                <img src="../images/filter-black.png" class="image">
                            </div>
                            <div class="filter-content-title">Search Filters</div>
                        </div>

                        <form id="filter-form">
                            <div class="filter-content">
                                <div class="filter-container-item">
                                    <div class="filter-title">By Category</div>
                                    <?php foreach ($categories as $category): ?>
                                        <div class="filter-item">
                                            <input type="checkbox" name="categories[]" value="<?= strtolower(str_replace(' ', '-', $category)) ?>">
                                            <label for="<?= strtolower(str_replace(' ', '-', $category)) ?>"> <?= htmlspecialchars($category) ?> </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="filter-container-item">
                                    <div class="filter-title">By Date</div>
                                    <input type="date" class="filter-date" name="filter_date">
                                </div>

                                <div class="button button-clear" id="clear-filters">Clear All</div>
                            </div>
                        </form>

                    </div>


                    <div id="search-results" class="container-contents-body">

                        <?php
                        // Check if the user is logged in
                        if (!isset($_SESSION['user_id'])) {
                            die('User not logged in.');
                        }

                        // Get user_id from session
                        $user_id = $_SESSION['user_id'];

                        try {
                            // Prepare and execute the SQL query using PDO
                            $sql = "
                                    SELECT 
                                        b.book_id,
                                        b.title,
                                        b.image,
                                        b.authors,
                                        b.categories, 
                                        br.borrow_date,
                                        IFNULL(ROUND(AVG(r.ratings), 2), 0) as avg_rating
                                    FROM 
                                        borrow br
                                    JOIN 
                                        books b ON br.book_id = b.book_id
                                    LEFT JOIN 
                                        ratings r ON b.book_id = r.book_id
                                    WHERE 
                                        br.user_id = ?
                                    GROUP BY 
                                        b.book_id, br.borrow_date
                                    ORDER BY 
                                        br.borrow_date DESC
                                ";

                            // Prepare the statement
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(1, $user_id, PDO::PARAM_INT); // Bind the user_id

                            // Execute the statement
                            $stmt->execute();

                            // Fetch the results as an associative array
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Group results by borrow_date
                            $groupedResults = [];
                            foreach ($result as $row) {
                                $groupedResults[$row['borrow_date']][] = $row;
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }

                        ?>

                        <div class="contents">
                            <?php foreach ($groupedResults as $date => $books): ?>
                                <div class="row row-between">
                                    <div><?php echo htmlspecialchars($date); ?></div>
                                    <div class="button button-view-more" onclick="goToResults()">View More</div>
                                </div>

                                <div class="row-books-container">
                                    <div class="arrow-left">
                                        <div class="arrow-image">
                                            <img src="../images/prev-black.png" alt="" class="image">
                                        </div>
                                    </div>

                                    <div class="row-books">
                                        <?php foreach ($books as $book): ?>
                                            <div class="container-books" id="book-<?php echo htmlspecialchars($book['book_id']); ?>">
                                                <div class="books-image">
                                                    <img src="../book_images/<?php echo htmlspecialchars($book['image']); ?>" class="image">
                                                </div>
                                                <div class="books-name"><?php echo htmlspecialchars($book['title']); ?></div>
                                                <div class="books-author" style="display: none;"><?php echo htmlspecialchars($book['authors']); ?></div>
                                                <div class="books-category"><?php echo htmlspecialchars($book['categories']); ?></div>
                                                <div class="books-ratings" style="display: none;"><?php echo htmlspecialchars($book['avg_rating']); ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="arrow-right">
                                        <div class="arrow-image">
                                            <img src="../images/next-black.png" alt="" class="image">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>


                        <div id="not-found-message" class="container-unavailable" style="display: none;">
                            <div class="unavailable-image">
                                <img src="../images/no-books.png" class="image">
                            </div>
                            <div class="unavailable-text">Not Found</div>
                        </div>


                    </div>

                </div>


            </div>

            <div class="row-books-contents" id="book-details" style="display: none;">
                <div class="container-books-contents">
                    <div class="books-contents-image">Image</div>
                    <div class="books-contents">

                        <div class="row row-between">
                            <div class="books-contents-name">Book Sample</div>
                            <div class="button button-close">&times;</div>
                        </div>

                        <div class="books-contents-author">Book Author</div>
                        <div class="books-contents-ratings" style="display:none"></div>

                        <div class="row">
                            <div class="star-rating">
                                <span class="star" data-value="1">&#9733;</span>
                                <span class="star" data-value="2">&#9733;</span>
                                <span class="star" data-value="3">&#9733;</span>
                                <span class="star" data-value="4">&#9733;</span>
                                <span class="star" data-value="5">&#9733;</span>
                            </div>

                            <div class="ratings-description">
                                <div class="ratings-number"> </div>&nbspout of 5
                            </div>
                        </div>


                        <div class="row">
                            <div class="button button-borrow">BORROW</div>
                            <div class="button button-bookmark"><img src="../images/bookmark-white.png" alt=""></div>
                            <div class="button button-ratings" onclick="openRateModal()"><img src="../images/star-white.png" alt=""></div>
                        </div>

                        <?php include 'modal/add_rating_modal.php'; ?>

                    </div>
                </div>

                <script src="js/book-details-toggle.js"></script>
            </div>

        </div>


        <div class="container-footer">

            <?php include 'footer.php'; ?>

        </div>

    </div>
</body>



</html>


<script src="js/sidebar.js"></script>
<script src="js/book-details-toggle.js"></script>
<script src="js/book-scroll.js"></script>
<script src="js/book-with-date-filter.js"></script>