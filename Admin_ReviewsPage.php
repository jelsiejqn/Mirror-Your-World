<?php
require "dbconnect.php";

// Handle show/hide actions
if (isset($_POST['action']) && isset($_POST['review_id'])) {
    $review_id = $_POST['review_id'];
    $action = $_POST['action'];

    if ($action === 'show') {
        $update_sql = "UPDATE reviewstbl SET approved = 1 WHERE review_id = ?";
    } else if ($action === 'hide') {
        $update_sql = "UPDATE reviewstbl SET approved = 0 WHERE review_id = ?";
    }

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to prevent form resubmission
    header("Location: Admin_ReviewsPage.php");
    exit();
}

// Base SQL for all queries
$base_sql = "SELECT 
                r.review_id,
                u.username,
                r.rating,
                r.comment,
                r.review_date,
                r.approved
            FROM 
                reviewstbl r
            JOIN 
                userstbl u ON r.user_id = u.user_id";

// Query for all reviews
$all_reviews_sql = $base_sql . " ORDER BY r.rating DESC;";

// Query for hidden reviews
$hidden_reviews_sql = $base_sql . " WHERE r.approved != 1 ORDER BY r.rating DESC;";

// Query for shown reviews
$shown_reviews_sql = $base_sql . " WHERE r.approved = 1 ORDER BY r.rating DESC;";

// Execute the queries
$all_reviews_result = $conn->query($all_reviews_sql);
if (!$all_reviews_result) {
    die("Error executing all reviews query: " . $conn->error);
}

$hidden_reviews_result = $conn->query($hidden_reviews_sql);
if (!$hidden_reviews_result) {
    die("Error executing hidden reviews query: " . $conn->error);
}

$shown_reviews_result = $conn->query($shown_reviews_sql);
if (!$shown_reviews_result) {
    die("Error executing shown reviews query: " . $conn->error);
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews | Mirror Your World </title>


    <link rel="stylesheet" href="Style/Admin_ReviewsPageCSS.css" />
    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/Calendar.css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .shown-tag {
            background-color: #4CAF50;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
            margin-left: 10px;
        }

        .hidden-tag {
            background-color: #ff9800;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
            margin-left: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            margin-top: 5px;
        }

        .show-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .hide-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .shown-review {
            border-left: 4px solid #4CAF50 !important;
        }

        .hidden-review {
            border-left: 4px solid #ff9800 !important;
        }
    </style>
</head>

<body>

    <!-- Required -->

    <div class="navbar">
        <a href="Admin_ShowcasePage.php">Showcase</a>
        <a href="Admin_AppointmentsPage.php">Appointments</a>
        <!-- <a href="Admin_FAQPage.php">FAQ</a> -->
        <a href="Admin_ReviewsPage.php">Reviews</a>

    </div>


    <!-- Calendar -->

    <div class="logo">
        <img src="Assets/icon_Logo.png" alt="Logo" style="width: 30px">
    </div>

    <div class="calendar-wrapper">
        <img src="Assets/icon_calendar.png" class="calntime-cal_img" alt="calendar" style="width: 40px; cursor: pointer;">
    </div>


    <div id="calntime-modal" class="calntime-modal" style="display: none;">
        <div class="calntime-modal-content">
            <span class="calntime-close">&times;</span>
            <h2 class="calntime-title">Calendar Availability
                <br>
                <p class="calntime-description"> Please block off unavailable dates. </p>

            </h2>

            <center>
                <div id="calntime-datepicker"></div>
                <div class="calntime-buttons">
                    <button id="calntime-block">Block Date</button>
                    <button id="calntime-unblock">Unblock Date</button>
                    <button id="calntime-update">Update</button>
                </div>
        </div>
    </div>


    <!-- Calendar -->


    <div class="profile-container" style="position: fixed; top: 10px; right: 20px; z-index: 1000; border-radius: 20px;">
        <button class="btn dropdown-trigger" data-target="dropdown1" style=" border-radius: 20px; padding: 0; background-color: transparent; border: none; cursor: pointer;" onclick="toggleDropdown()">
            <img src="Assets/icon_Profile.png" class="iconProfile" alt="Profile Icon" width="40px" height="40px" style="width: 25px; height: 25px; object-fit: cover; cursor: pointer; transition: filter 0.3s ease;" onmouseover="this.style.filter='invert(1)';" onmouseout="this.style.filter='invert(0)';" />
        </button>

        <br />
        <ul id="dropdown1" class="dropdown-content" style="transition: 0.3s; display: none; position: absolute; top: 60px; right: 0; background-color: white; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15); width: 200px; padding: 0; margin: 0;">
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                <a href="Admin_AccountPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Account</a>
            </li>
            <form method="POST" action="Admin_LoginPage.php">
                <li style="list-style: none; margin: 0; padding: 10px;">
                    <a href="Admin_LogoutProcess.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Logout</a>
                </li>
            </form>
        </ul>
    </div>

    <!-- Required -->


    <div class="dashboard-container">
        <!-- Sidebar (Options) -->
        <div class="sidebar">
            <h3>Dashboard</h3>
            <a href="#" onclick="showContent('active-bookings')" class="active">Client Reviews</a>
            <a href="#" onclick="showContent('hidden-reviews')">Hidden Reviews</a>
            <a href="#" onclick="showContent('shown-reviews')">Shown Reviews</a>
        </div>

        <!-- Main Content (Forms to edit) -->
        <div class="content">

            <!-- All Reviews -->
            <div class="section" id="active-bookings">
                <h2>All Client Reviews</h2>

                <table class="sortby-container">
                    <tr>
                        <td>
                            <select id="sortByDropdown" onchange="sortBookings('all')">
                                <option value="recent">Sort by</option>
                                <option value="recent">Highest</option>
                                <option value="oldest">Lowest</option>
                                <option value="hidden">Hidden</option>
                                <option value="shown">Shown</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <br>

                <center>

                    <?php
                    if ($all_reviews_result->num_rows > 0) {
                        $review_count = 1;
                        while ($row = $all_reviews_result->fetch_assoc()) {
                            $reviewClass = $row["approved"] == 1 ? "shown-review" : "hidden-review";
                    ?>
                            <table class="booking-container <?php echo $reviewClass; ?>">
                                <tr>
                                    <td class="td-date">
                                        <h1><?php echo $review_count; ?></h1>
                                    </td>
                                    <td class="td-details">
                                        <h5>
                                            <?php
                                            echo isset($row["username"]) ? $row["username"] : "Unknown User";
                                            ?>
                                            <?php if ($row["approved"] == 1): ?>
                                                <span class="shown-tag">Shown</span>
                                            <?php else: ?>
                                                <span class="hidden-tag">Hidden</span>
                                            <?php endif; ?>
                                            <br>
                                        </h5>
                                    </td>
                                    <td class="td-booker">
                                        <h5>
                                            <?php echo date('F j, Y', strtotime($row["review_date"])); ?>
                                            <br>
                                        </h5>
                                    </td>
                                    <td class="td-satisfaction">
                                        <center>
                                            <?php echo $row["rating"]; ?>
                                        </center>
                                    </td>
                                    <td class="td-review">
                                        <h5>
                                            <?php echo $row["comment"]; ?>
                                        </h5>
                                        <div class="action-buttons">
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="review_id" value="<?php echo $row["review_id"]; ?>">
                                                <input type="hidden" name="action" value="show">
                                                <button type="submit" class="show-btn">Show</button>
                                            </form>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="review_id" value="<?php echo $row["review_id"]; ?>">
                                                <input type="hidden" name="action" value="hide">
                                                <button type="submit" class="hide-btn">Hide</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-date"></td>

                                    <td class="td-details">
                                        <h6>Username</h6>
                                    </td>
                                    <td class="td-booker">
                                        <h6>Date</h6>
                                    </td>
                                    <td class="td-satisfaction">
                                        <h6>Rating (1-5)</h6>
                                    </td>
                                    <td class="td-review">
                                        <h6>Review</h6>
                                    </td>
                                </tr>
                            </table>
                    <?php
                            $review_count++;
                        }
                    } else {
                        echo "No reviews found.";
                    }
                    ?>
                </center>
            </div>

            <!-- Hidden Reviews -->
            <div class="section" id="hidden-reviews" style="display: none;">
                <h2>Hidden Reviews</h2>
                <br>
                <center>
                    <?php
                    if ($hidden_reviews_result->num_rows > 0) {
                        $review_count = 1;
                        while ($row = $hidden_reviews_result->fetch_assoc()) {
                    ?>
                            <table class="booking-container hidden-review">
                                <tr>
                                    <td class="td-date">
                                        <h1><?php echo $review_count; ?></h1>
                                    </td>
                                    <td class="td-details">
                                        <h5>
                                            <?php
                                            echo isset($row["username"]) ? $row["username"] : "Unknown User";
                                            ?>
                                            <span class="hidden-tag">Hidden</span>
                                            <br>
                                        </h5>
                                    </td>
                                    <td class="td-booker">
                                        <h5>
                                            <?php echo date('F j, Y', strtotime($row["review_date"])); ?>
                                            <br>
                                        </h5>
                                    </td>
                                    <td class="td-satisfaction">
                                        <center>
                                            <?php echo $row["rating"]; ?>
                                        </center>
                                    </td>
                                    <td class="td-review">
                                        <h5>
                                            <?php echo $row["comment"]; ?>
                                        </h5>
                                        <div class="action-buttons">
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="review_id" value="<?php echo $row["review_id"]; ?>">
                                                <input type="hidden" name="action" value="show">
                                                <button type="submit" class="show-btn">Show</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-date"></td>
                                    <td class="td-details">
                                        <h6>Username</h6>
                                    </td>
                                    <td class="td-booker">
                                        <h6>Date</h6>
                                    </td>
                                    <td class="td-satisfaction">
                                        <h6>Rating (1-5)</h6>
                                    </td>
                                    <td class="td-review">
                                        <h6>Review</h6>
                                    </td>
                                </tr>
                            </table>
                    <?php
                            $review_count++;
                        }
                        if ($review_count == 1) {
                            echo "No hidden reviews found.";
                        }
                    } else {
                        echo "No hidden reviews found.";
                    }
                    ?>
                </center>
            </div>

            <!-- Shown Reviews -->
            <div class="section" id="shown-reviews" style="display: none;">
                <h2>Shown Reviews</h2>
                <br>
                <center>
                    <?php
                    if ($shown_reviews_result->num_rows > 0) {
                        $review_count = 1;
                        while ($row = $shown_reviews_result->fetch_assoc()) {
                    ?>
                            <table class="booking-container shown-review">
                                <tr>
                                    <td class="td-date">
                                        <h1><?php echo $review_count; ?></h1>
                                    </td>
                                    <td class="td-details">
                                        <h5>
                                            <?php
                                            echo isset($row["username"]) ? $row["username"] : "Unknown User";
                                            ?>
                                            <span class="shown-tag">Shown</span>
                                            <br>
                                        </h5>
                                    </td>
                                    <td class="td-booker">
                                        <h5>
                                            <?php echo date('F j, Y', strtotime($row["review_date"])); ?>
                                            <br>
                                        </h5>
                                    </td>
                                    <td class="td-satisfaction">
                                        <center>
                                            <?php echo $row["rating"]; ?>
                                        </center>
                                    </td>
                                    <td class="td-review">
                                        <h5>
                                            <?php echo $row["comment"]; ?>
                                        </h5>
                                        <div class="action-buttons">
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="review_id" value="<?php echo $row["review_id"]; ?>">
                                                <input type="hidden" name="action" value="hide">
                                                <button type="submit" class="hide-btn">Hide</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-date"></td>
                                    <td class="td-details">
                                        <h6>Username</h6>
                                    </td>
                                    <td class="td-booker">
                                        <h6>Date</h6>
                                    </td>
                                    <td class="td-satisfaction">
                                        <h6>Rating (1-5)</h6>
                                    </td>
                                    <td class="td-review">
                                        <h6>Review</h6>
                                    </td>
                                </tr>
                            </table>
                    <?php
                            $review_count++;
                        }
                        if ($review_count == 1) {
                            echo "No shown reviews found.";
                        }
                    } else {
                        echo "No shown reviews found.";
                    }
                    ?>
                </center>
            </div>
        </div>
    </div>
</body>

<script>
    // Function to toggle the visibility of the dropdown content
    function toggleDropdown() {
        var dropdown = document.getElementById('dropdown1');
        // Toggle the display of the dropdown menu
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }

    // Close the dropdown if the user clicks anywhere outside the dropdown
    window.onclick = function(event) {
        // Check if the click is outside the dropdown or icon
        if (!event.target.matches('.dropdown-trigger') && !event.target.matches('.dropdown-trigger img')) {
            var dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(function(dropdown) {
                dropdown.style.display = 'none'; // Close the dropdown
            });
        }
    };

    // Function to show content when a sidebar link is clicked
    function showContent(sectionId) {
        // Hide all sections
        var sections = document.querySelectorAll('.section');
        sections.forEach(function(section) {
            section.style.display = 'none';
        });

        // Show the clicked section
        var activeSection = document.getElementById(sectionId);
        if (activeSection) {
            activeSection.style.display = 'block';
        }

        // Update active state in sidebar
        var links = document.querySelectorAll('.sidebar a');
        links.forEach(function(link) {
            link.classList.remove('active');
            if (link.getAttribute('onclick').includes(sectionId)) {
                link.classList.add('active');
            }
        });
    }

    // Function to sort bookings based on the selected option
    function sortBookings(section = 'all') {
        // Get the selected sort option
        const sortOption = document.getElementById('sortByDropdown').value;

        // Determine which section to sort
        let selector;
        if (section === 'all') {
            selector = '#active-bookings .booking-container';
        } else if (section === 'hidden') {
            selector = '#hidden-reviews .booking-container';
        } else if (section === 'shown') {
            selector = '#shown-reviews .booking-container';
        }

        // Get all booking containers for the selected section
        const bookingContainers = Array.from(document.querySelectorAll(selector));
        if (bookingContainers.length === 0) return;

        // Get the parent element containing all booking containers
        const parentElement = bookingContainers[0].parentNode;

        // Sort the booking containers based on the criteria
        bookingContainers.sort(function(a, b) {
            // Extract values for sorting
            const ratingA = parseFloat(a.querySelector('.td-satisfaction center').textContent.trim());
            const ratingB = parseFloat(b.querySelector('.td-satisfaction center').textContent.trim());
            const isShownA = a.classList.contains('shown-review');
            const isShownB = b.classList.contains('shown-review');

            // Sort based on the selected option
            if (sortOption === 'recent') { // Highest rating first
                return ratingB - ratingA;
            } else if (sortOption === 'oldest') { // Lowest rating first
                return ratingA - ratingB;
            } else if (sortOption === 'hidden') { // Hidden reviews first
                return isShownA - isShownB;
            } else if (sortOption === 'shown') { // Shown reviews first
                return isShownB - isShownA;
            }

            return 0;
        });

        // Clear the parent element
        while (parentElement.firstChild) {
            parentElement.removeChild(parentElement.firstChild);
        }

        // Re-append the sorted booking containers
        bookingContainers.forEach(function(container, index) {
            // Update the review number (first column)
            container.querySelector('.td-date h1').textContent = index + 1;
            parentElement.appendChild(container);
        });
    }

    // Calendar functionality (preserved from original)
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("calntime-modal");
        const img = document.querySelector(".calntime-cal_img");
        const closeBtn = document.querySelector(".calntime-close");

        flatpickr("#calntime-datepicker", {
            inline: true,
            enableTime: false,
            dateFormat: "Y-m-d"
        });

        img.onclick = function() {
            modal.style.display = "flex";
        }

        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });

    // Ensure the function runs when the page loads to apply default sorting
    document.addEventListener('DOMContentLoaded', function() {
        // Sort by highest rating by default
        document.getElementById('sortByDropdown').value = 'recent';
        sortBookings('all');
    });
</script>

</html>