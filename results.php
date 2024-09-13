<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Results</title>

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

                    <?php
                    $categoryFilter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
                    include '../functions/fetch_categories.php';

                    // Prepare the SQL query
                    $query = "SELECT * FROM books";
                    if ($categoryFilter) {
                        $query .= " WHERE categories LIKE '%" . $categoryFilter . "%'";
                    }

                    // Execute the query
                    $booksResult = mysqli_query($conn, $query);

                    // Check for errors
                    if (!$booksResult) {
                        die("Query failed: " . mysqli_error($conn));
                    }

                    // Fetch books
                    $books = mysqli_fetch_all($booksResult, MYSQLI_ASSOC);
                    ?>

                    <div class="contents-title">
                        Results for "<?php echo htmlspecialchars($categoryFilter); ?>"
                    </div>

                    <div class="container-search row">
                        <input type="text" class="search">

                        <div class="container-search-image">
                            <div class="search-image">
                                <img src="../images/search-black.png" class="image">
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row">


                    <div class="result-contents">

                        <div class="row-contents-center" id="bookContainer">
                            <?php if ($books): ?>
                                <?php foreach ($books as $book): ?>
                                    <div class="container-books-2">
                                        <div class="books-image-2">
                                            <img src="../book_images/<?= htmlspecialchars($book['image']) ?>" class="image">
                                        </div>
                                        <div class="books-name"><?= htmlspecialchars($book['title']) ?></div>
                                        <div class="books-author"><?= htmlspecialchars($book['authors']) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-results">No books found for the selected category.</div>
                            <?php endif; ?>
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
                                            <div class="ratings-number"></div>&nbspout of 5
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="button button-borrow">BORROW</div>
                                        <div class="button button-bookmark">
                                            <img src="../images/bookmark-white.png" alt="">
                                        </div>
                                        <div class="button button-ratings" onclick="openRateModal()">
                                            <img src="../images/star-white.png" alt="">
                                        </div>
                                    </div>
                                    <?php include 'modal/add_rating_modal.php'; ?>
                                </div>
                            </div>
                        </div>






                        <div class="row row-center">

                            <div class="pagination-controls">
                                Items per page:
                                <select class="page-select" id="itemsPerPage">
                                    <option value="20">20</option>
                                    <option value="40">40</option>
                                    <option value="60">60</option>
                                </select>
                            </div>

                            <div class="pagination-controls">
                                <button class="button button-page" id="prevPage">Previous</button>
                                <span class="page-number" id="pageInfo"></span>
                                <button class="button button-page" id="nextPage">Next</button>
                            </div>

                        </div>





                    </div>




                </div>




            </div>








        </div>





        <div class="container-footer">
            <?php include 'footer.php'; ?>
        </div>


    </div>
</body>



</html>

<script src="js/sidebar.js"></script>
<!-- <script src="js/book-list-pagination.js"></script> -->


<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookContainer = document.getElementById('bookContainer');
    const itemsPerPageSelect = document.getElementById('itemsPerPage');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const pageInfo = document.getElementById('pageInfo');
    const searchInput = document.querySelector('.search');

    let itemsPerPage = parseInt(itemsPerPageSelect.value) || 20;
    let currentPage = 1;
    let lastClickedBook = null;

    // Store original book data
    let originalBooks = Array.from(bookContainer.children).map(bookDiv => ({
        name: bookDiv.querySelector('.books-name') ? bookDiv.querySelector('.books-name').textContent : '',
        image: bookDiv.querySelector('.books-image-2 img') ? bookDiv.querySelector('.books-image-2 img').src : 'book-image-placeholder.png',
        author: bookDiv.querySelector('.books-author') ? bookDiv.querySelector('.books-author').textContent : ''
    }));
    let filteredBooks = [...originalBooks];

    function addBookClickListeners() {
        const books = document.querySelectorAll('.container-books-2');

        books.forEach(book => {
            book.addEventListener('click', () => {
                lastClickedBook = book;

                const bookTitle = book.querySelector('.books-name-2').textContent;
                const bookImage = book.querySelector('.books-image-2 img').src;
                const bookAuthor = book.querySelector('.books-author').textContent || 'Unknown Author';
                const bookRating = book.querySelector('.books-ratings') ? book.querySelector('.books-ratings').textContent : '0';

                const bookDetailsContainer = document.getElementById('book-details');
                bookDetailsContainer.querySelector('.books-contents-name').textContent = bookTitle;
                bookDetailsContainer.querySelector('.books-contents-image').innerHTML = `<img src="${bookImage}" class="image">`;
                bookDetailsContainer.querySelector('.books-contents-author').textContent = bookAuthor;
                bookDetailsContainer.querySelector('.books-contents-ratings').textContent = bookRating;
                bookDetailsContainer.querySelector('.ratings-number').textContent = bookRating;

                bookDetailsContainer.style.display = 'flex';
                bookDetailsContainer.scrollIntoView({
                    behavior: 'smooth',
                    block: 'end'
                });

                const stars = document.querySelectorAll('.star');
                let rating = parseFloat(bookRating);

                if (!isNaN(rating)) {
                    rating = Math.round(rating);

                    stars.forEach(star => {
                        const value = parseFloat(star.getAttribute('data-value'));
                        if (value <= rating) {
                            star.classList.add('active');
                        } else {
                            star.classList.remove('active');
                        }
                    });
                }
            });
        });

        const closeButton = document.querySelector('.button-close');
        closeButton.addEventListener('click', () => {
            document.getElementById('book-details').style.display = 'none';

            if (lastClickedBook) {
                lastClickedBook.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    }

    function updateDisplay() {
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedBooks = filteredBooks.slice(start, end);

        bookContainer.innerHTML = '';

        if (paginatedBooks.length === 0) {
            bookContainer.innerHTML = `
                <div class="container-unavailable">
                    <div class="unavailable-image">
                        <img src="../images/no-books.png" class="image">
                    </div>
                    <div class="unavailable-text">Not Found</div>
                </div>
            `;
        } else {
            paginatedBooks.forEach(book => {
                const bookDiv = document.createElement('div');
                bookDiv.classList.add('container-books-2');
                bookDiv.innerHTML = `
                    <div class="books-image-2">
                        <img src="${book.image}" class="image">
                    </div>
                    <div class="books-name-2">${book.name}</div>
                    <div class="books-author" style="display: none;">${book.author}</div>
                `;
                bookContainer.appendChild(bookDiv);
            });
        }

        const totalPages = Math.ceil(filteredBooks.length / itemsPerPage);
        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
        prevPageButton.disabled = currentPage === 1;
        nextPageButton.disabled = currentPage === totalPages;

        addBookClickListeners(); // Add the click listeners to the newly displayed books
    }

    itemsPerPageSelect.addEventListener('change', function() {
        itemsPerPage = parseInt(this.value) || 20;
        currentPage = 1;
        updateDisplay();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    prevPageButton.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            updateDisplay();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    nextPageButton.addEventListener('click', function() {
        const totalPages = Math.ceil(filteredBooks.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            updateDisplay();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();

        if (query) {
            filteredBooks = originalBooks.filter(book => book.name.toLowerCase().includes(query));
        } else {
            filteredBooks = [...originalBooks];
        }

        currentPage = 1;
        updateDisplay();
    });

    updateDisplay(); // Initial display update with listeners attached
});
</script>
