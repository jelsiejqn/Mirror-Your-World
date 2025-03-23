<?php
require "dbconnect.php";

$sql = "SELECT 
            r.review_id,
            u.username,
            r.rating,
            r.comment,
            r.review_date
        FROM 
            reviewstbl r
        JOIN 
            userstbl u ON r.user_id = u.user_id
        ORDER BY 
            r.rating DESC;";

$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Check if there are any reviews
if ($result->num_rows > 0) {
    // Data will be fetched and used in the HTML below
} else {
    echo "No reviews found.";
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
        <img src="Assets/icon_calendar.png" class="calntime-cal_img" alt="calendar" style="width: 30px; cursor: pointer;">
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
            <a href="#" onclick="showContent('active-bookings')">Client Reviews</a>
        </div>

        <!-- Main Content (Forms to edit) -->
        <div class="content">

            <!-- Active Bookings -->
            <div class="section" id="active-bookings">
                <h2>Client Reviews</h2>
                <!-- Yung 12 hours pagitan na pwede silamagcancel both client and user -->

                <table class="sortby-container">
                    <tr>
                        <td>
                            <select id="sortByDropdown" onchange="sortBookings()">
                                <option value="recent">Sort by</option>
                                <option value="recent">Highest</option>
                                <option value="oldest">Lowest</option>
                            </select>
                        </td>

                    </tr>
                </table>
                <br>

                <center>

                    <?php
                    if ($result->num_rows > 0) {
                        $review_count = 1;
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <table class="booking-container">
                                <tr>
                                    <td class="td-date">
                                        <h1><?php echo $review_count; ?></h1>
                                    </td>
                                    <td class="td-details">
                                        <h5>
                                            <?php
                                            echo isset($row["username"]) ? $row["username"] : "Unknown User";
                                            ?>
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

                    <!-- End Review Container -->



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
    }

    function openModal() {
        document.getElementById('cancelModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('cancelModal').classList.remove('show');
    }

    function submitCancel() {
        const selectedReason = document.querySelector('input[name="reason"]:checked');
        if (selectedReason) {
            alert('Cancellation reason submitted: ' + selectedReason.value);
            closeModal();
        } else {
            alert('Please select a reason before submitting.');
        }
    }

    // Calendar

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

    
    // Calendar functionality
document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("calntime-modal");
    const img = document.querySelector(".calntime-cal_img");
    const closeBtn = document.querySelector(".calntime-close");
    const blockBtn = document.getElementById("calntime-block");
    const unblockBtn = document.getElementById("calntime-unblock");
    const updateBtn = document.getElementById("calntime-update");
    
    // Store blocked dates
    let blockedDates = [];
    
    // Initialize calendar
    const calendar = flatpickr("#calntime-datepicker", {
        inline: true,
        enableTime: false,
        dateFormat: "Y-m-d",
        onReady: function() {
            // Load blocked dates from the database
            loadBlockedDates();
        }
    });
    
    // Function to load blocked dates from the database
    function loadBlockedDates() {
        fetch('get_blocked_dates.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    blockedDates = data.dates;
                    updateCalendarDisplay();
                } else {
                    console.error("Failed to load blocked dates:", data.message);
                }
            })
            .catch(error => console.error("Error loading blocked dates:", error));
    }
    
    // Function to update the calendar display with blocked dates
    function updateCalendarDisplay() {
        // Get all date cells in the calendar
        const dateCells = document.querySelectorAll(".flatpickr-day");
        
        // Reset all cells
        dateCells.forEach(cell => {
            cell.classList.remove("blocked-date");
        });
        
        // Mark blocked dates
        blockedDates.forEach(date => {
            dateCells.forEach(cell => {
                const cellDate = cell.getAttribute("aria-label");
                if (cellDate && cellDate === formatDate(date)) {
                    cell.classList.add("blocked-date");
                }
            });
        });
    }
    
    // Format date to match flatpickr format
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const months = [
            "January", "February", "March", "April", "May", "June", 
            "July", "August", "September", "October", "November", "December"
        ];
        return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
    }
    
    // Block date button click
    blockBtn.addEventListener("click", function() {
        const selectedDate = calendar.selectedDates[0];
        if (selectedDate) {
            const formattedDate = formatDateForDB(selectedDate);
            blockDate(formattedDate);
        } else {
            alert("Please select a date to block");
        }
    });
    
    // Unblock date button click
    unblockBtn.addEventListener("click", function() {
        const selectedDate = calendar.selectedDates[0];
        if (selectedDate) {
            const formattedDate = formatDateForDB(selectedDate);
            unblockDate(formattedDate);
        } else {
            alert("Please select a date to unblock");
        }
    });
    
    // Update calendar button click
    updateBtn.addEventListener("click", function() {
        // Reload blocked dates from the server
        loadBlockedDates();
        alert("Calendar updated successfully");
    });
    
    // Format date for database (YYYY-MM-DD)
    function formatDateForDB(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    // Function to block a date
    function blockDate(date) {
        const formData = new FormData();
        formData.append('action', 'block');
        formData.append('date', date);
        
        fetch('Admin_CalendarFunctions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add to local blocked dates array if not already there
                if (!blockedDates.includes(date)) {
                    blockedDates.push(date);
                }
                updateCalendarDisplay();
                alert("Date blocked successfully");
            } else {
                alert("Failed to block date: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error blocking date:", error);
            alert("An error occurred while blocking the date");
        });
    }
    
    // Function to unblock a date
    function unblockDate(date) {
        const formData = new FormData();
        formData.append('action', 'unblock');
        formData.append('date', date);
        
        fetch('Admin_CalendarFunctions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove from local blocked dates array
                blockedDates = blockedDates.filter(d => d !== date);
                updateCalendarDisplay();
                alert("Date unblocked successfully");
            } else {
                alert("Failed to unblock date: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error unblocking date:", error);
            alert("An error occurred while unblocking the date");
        });
    }
    
    // Open modal
    img.onclick = function() {
        modal.style.display = "flex";
        loadBlockedDates(); // Refresh dates when opening
    }
    
    // Close modal
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});

// Function to sort bookings based on the selected option
function sortBookings() {
    // Get the selected sort option (highest or lowest rating)
    const sortOption = document.getElementById('sortByDropdown').value;
    
    // Get all booking containers
    const bookingContainers = Array.from(document.querySelectorAll('.booking-container'));
    
    // Get the parent element containing all booking containers
    const parentElement = bookingContainers[0].parentNode;
    
    // Sort the booking containers based on the rating
    bookingContainers.sort(function(a, b) {
        // Extract rating values from each container
        const ratingA = parseFloat(a.querySelector('.td-satisfaction center').textContent.trim());
        const ratingB = parseFloat(b.querySelector('.td-satisfaction center').textContent.trim());
        
        // Sort based on the selected option
        if (sortOption === 'recent') { // Highest rating first
            return ratingB - ratingA;
        } else if (sortOption === 'oldest') { // Lowest rating first
            return ratingA - ratingB;
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

// Ensure the function runs when the page loads to apply default sorting
document.addEventListener('DOMContentLoaded', function() {
    // Sort by highest rating by default
    document.getElementById('sortByDropdown').value = 'recent';
    sortBookings();
});
</script>


</html>