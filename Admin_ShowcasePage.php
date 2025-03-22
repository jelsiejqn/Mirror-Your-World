<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showcase | Mirror Your World </title>


    <link rel="stylesheet" href="Style/Admin_ShowcasePageCSS.css" />
    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/Calendar.css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>

<?php
require "dbconnect.php";

// Check if the form is submitted
if (isset($_POST['update-desc'])) {
    // Sanitize inputs
    $company_name = mysqli_real_escape_string($conn, $_POST['company-name']);
    $date = $_POST['date'];
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);

    // Handle file upload
    $image_path = '';
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0) {
        $target_dir = "uploads/"; // Folder where images will be saved
        $target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: 'There was an error uploading your file.'
                });
            </script>";
            exit();
        }
    }

    // Insert data into the showcase table
    if (!empty($image_path)) {
        $sql = "INSERT INTO showcasetbl (company_name, date, description, image_path) 
                VALUES ('$company_name', '$date', '$desc', '$image_path')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Your data has been inserted successfully.'
                }).then(() => {
                    window.location.href = 'Admin_ShowcasePage.php'; // Redirect to Showcase page
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Database Error',
                    text: 'Error inserting data: " . addslashes($conn->error) . "'
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Missing Image',
                text: 'Please upload an image.'
            });
        </script>";
    }
}

// Fetch showcase data
$sql_showcase = "SELECT * FROM showcasetbl ORDER BY date DESC";
$result_showcase = $conn->query($sql_showcase);

// Fetch clients data
$sql_clients = "SELECT * FROM clientstbl ORDER BY id DESC";
$result_clients = $conn->query($sql_clients);


$conn->close();
?>

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
            <a href="#" onclick="showContent('active-bookings')">Works</a>
            <a href="#" onclick="showContent('past-bookings')">People We've Worked With</a>
        </div>

        <!-- Main Content (Forms to edit) -->
        <div class="content">

            <!-- Active Bookings -->
            <div class="section" id="active-bookings">
                <h2>Company Works</h2>
                <!-- Yung 12 hours pagitan na pwede silamagcancel both client and user -->

                <table class="sortby-container">
                    <tr>
                        <td>
                            <select id="sortByDrop \down" onchange="sortBookings()">
                                <option value="recent">Sort by</option>
                                <option value="recent">Most Recent</option>
                                <option value="oldest">Oldest</option>
                            </select>
                        </td>
                    </tr>
                </table>

                <!-- Showcase Container -->
                <br>

                <!-- Showcase Container -->

                <center>

                    <table class="booking-container">
                        <tr>
                            <td class="td-date">
                                <form action="Admin_ShowcasePage.php" method="POST" enctype="multipart/form-data" id="showcaseForm" onsubmit="return validateForm()">
                                    <input type="hidden" name="table" value="showcase">

                                    <input type="file" name="fileUpload" id="fileUpload" accept="image/*" required>

                            </td>

                            <td class="td-details">

                                <textarea name="company-name" placeholder="Company Name" required></textarea>
                            </td>

                            <td class="td-booker">

                            <td class="td-booker">
                                <input type="date" name="date" placeholder="MM/DD/YYYY" required />
                            </td>

                            </td>

                            <td class="td-review">

                                <textarea name="desc" class="desc" placeholder="Description" required></textarea>
                                <br>
                            <td>
                                <button type="submit" name="update-desc" class="btn-add"> Add </button>
                            </td>
                        </tr>
                        </form>
                    </table>

                    <br>

                </center>
                <!-- End Showcase Container -->

                <center>
                    <table class="booking-container">
                        <tr>
                            <th>Image</th>
                            <th>Company Name</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>

                        <tr>
                            <td colspan='5'>
                                <hr>
                            </td>
                        </tr>

                        <?php
                        require "dbconnect.php";

                        // Fetch showcase data
                        $sql = "SELECT * FROM showcasetbl ORDER BY date DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='td-date'><img src='" . $row['image_path'] . "' width='' style='object-fit: cover;'></td>";
                                echo "<td class='td-details'><strong>" . htmlspecialchars($row['company_name']) . "</strong></td>";
                                echo "<td class='td-booker'>" . date('F j, Y', strtotime($row['date'])) . "</td>";
                                echo "<td class='td-review'>" . nl2br(htmlspecialchars($row['description'])) . "</td>";
                                echo "<td class='td-actions'>"; // New column for buttons
                                echo "<button class='btn btn-primary btn-edit edit-btn' data-id='" . $row['id'] . "' 
                data-company='" . htmlspecialchars($row['company_name']) . "' 
                data-date='" . $row['date'] . "' 
                data-description='" . htmlspecialchars($row['description']) . "' 
                data-image='" . $row['image_path'] . "'>Edit</button> | ";

                                echo "<button class='btn btn-danger btn-delete del-btn' data-id='" . $row['id'] . "'>Delete</button>";
                                echo "</td>";
                                echo "</tr>";
                                echo "<tr><td colspan='5'><hr></td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No records found.</td></tr>"; // colspan adjusted
                        }

                        $conn->close();
                        ?>
                    </table>
                </center>

            </div>

            <!-- Past Bookings -->
            <div class="section" id="past-bookings" style="display: none;">
                <h2>People We've Worked With</h2>

                <table class="sortby-container">
                    <tr>

                        <td>
                            <select id="sortByDropdown" onchange="sortBookings()">
                                <option value="recent">Sort by</option>
                                <option value="recent">Most Recent</option>
                                <option value="oldest">Oldest</option>
                            </select>
                        </td>
                </table>

                <br>

                <center>
                    <table class="booking-container">
                        <tr>
                            <td class="td-date">
                                <form action="Admin_AddClients.php" method="POST" enctype="multipart/form-data" id="clientForm" onsubmit="return validateClientForm()">
                                    <input type="hidden" name="table" value="client">
                                    <input type="file" name="fileUpload" id="fileUpload" accept="image/*" required>
                            </td>
                            <td class="td-details">
                                <textarea name="client-name" placeholder="Client Name" required></textarea>
                            </td>
                            <td class="td-booker">
                                <textarea name="client-role" placeholder="Client Role" required></textarea>
                            </td>
                            <td class="td-review">
                                <textarea name="desc" class="desc" placeholder="Description" required></textarea>
                                <br>
                            <td>
                                <button type="submit" name="update-desc" class="btn-add">Add</button>
                            </td>
                            </td>
                        </tr>
                        </form>
                    </table>
                    <br>

                    <table class="booking-container">
                        <tr>
                            <th>Image</th>
                            <th>Client Name</th>
                            <th>Client Role</th>
                            <th>Description</th>
                            <th>Actions</th>

                        <tr>
                            <td colspan='5'>
                                <hr>
                            </td>
                        </tr>
                        </tr>
                        <?php
                        if ($result_clients->num_rows > 0) {
                            while ($row = $result_clients->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='td-date'><img src='" . $row['image_path'] . "' width='' style='object-fit: cover;'></td>";
                                echo "<td class='td-details'><strong>" . htmlspecialchars($row['client_name']) . "</strong></td>";
                                echo "<td class='td-booker'>" . htmlspecialchars($row['client_role']) . "</td>";
                                echo "<td class='td-review'>" . nl2br(htmlspecialchars($row['description'])) . "</td>";
                                echo "<td class='td-actions'>";
                                echo "<button class='btn btn-primary btn-edit-client edit-btn' data-id='" . $row['id'] . "' 
                        data-name='" . htmlspecialchars($row['client_name']) . "' 
                        data-role='" . htmlspecialchars($row['client_role']) . "' 
                        data-description='" . htmlspecialchars($row['description']) . "' 
                        data-image='" . $row['image_path'] . "'>Edit</button> | ";
                                echo "<button class='btn btn-danger btn-delete-client del-btn' data-id='" . $row['id'] . "'>Delete</button>";
                                echo "</td>";
                                echo "</tr>";
                                echo "<tr><td colspan='5'><hr></td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No client records found.</td></tr>";
                        }
                        ?>
                    </table>
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

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-delete").forEach(button => {
            button.addEventListener("click", function() {
                let showcaseId = this.getAttribute("data-id");

                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("delete_showcase.php?id=" + showcaseId, {
                                method: "GET"
                            })
                            .then(response => response.text())
                            .then(data => {
                                Swal.fire("Deleted!", "The showcase has been deleted.", "success")
                                    .then(() => location.reload());
                            })
                            .catch(error => {
                                Swal.fire("Error", "Something went wrong!", "error");
                            });
                    }
                });
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-edit").forEach(button => {
            button.addEventListener("click", function() {
                let showcaseId = this.getAttribute("data-id");
                let companyName = this.getAttribute("data-company");
                let date = this.getAttribute("data-date");
                let description = this.getAttribute("data-description");
                let imagePath = this.getAttribute("data-image");

                Swal.fire({
                    title: "Edit Showcase",
                    width: 400, // Fixed width to prevent overflow
                    padding: "1.2em",
                    background: "#f8f9fa",
                    confirmButtonColor: "#007bff",
                    cancelButtonColor: "#dc3545",
                    showCloseButton: true, // Add "X" button
                    allowOutsideClick: false, // Prevent accidental closing
                    backdrop: true, // Ensures it stays in front
                    html: `
                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%; box-sizing: border-box;">
                        <img src="${imagePath}" width="100" style="border-radius: 8px; margin-bottom: 10px;">
                        <label for="company" style="font-size: 14px; align-self: flex-start;">Company Name:</label>
                        <input type="text" id="company" class="swal2-input" style="width: 100%; font-size: 14px; box-sizing: border-box;" value="${companyName}">
                        
                        <label for="date" style="font-size: 14px; align-self: flex-start;">Date:</label>
                        <input type="date" id="date" class="swal2-input" style="width: 100%; font-size: 14px; box-sizing: border-box;" value="${date}">

                        <label for="description" style="font-size: 14px; align-self: flex-start;">Description:</label>
                        <textarea id="description" class="swal2-textarea" style="width: 100%; font-size: 14px; height: 100px; box-sizing: border-box;">${description}</textarea>

                        <label for="image" style="font-size: 14px; align-self: flex-start;">Image:</label>
                        <input type="file" id="image" class="swal2-file" style="width: 100%; font-size: 12px; box-sizing: border-box;">
                    </div>
                `,
                    showCancelButton: true,
                    confirmButtonText: "Update",
                    cancelButtonText: "Cancel",
                    preConfirm: () => {
                        let formData = new FormData();
                        formData.append("id", showcaseId);
                        formData.append("company_name", document.getElementById("company").value);
                        formData.append("date", document.getElementById("date").value);
                        formData.append("description", document.getElementById("description").value);
                        let imageFile = document.getElementById("image").files[0];
                        if (imageFile) {
                            formData.append("image", imageFile);
                        }

                        return fetch("edit_showcase.php", {
                                method: "POST",
                                body: formData
                            })
                            .then(response => response.text())
                            .then(data => {
                                if (data.trim() === "Success") {
                                    Swal.fire("Updated!", "The showcase has been updated.", "success")
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire("Error", "Something went wrong!", "error");
                                }
                            })
                            .catch(error => {
                                Swal.fire("Error", "Something went wrong!", "error");
                            });
                    }
                });
            });
        });
    });

    //Delete Client
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-delete-client").forEach(button => {
            button.addEventListener("click", function() {
                let clientId = this.getAttribute("data-id");

                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("delete_client.php?id=" + clientId, {
                                method: "GET"
                            })
                            .then(response => response.text())
                            .then(data => {
                                Swal.fire("Deleted!", "The client has been deleted.", "success")
                                    .then(() => location.reload());
                            })
                            .catch(error => {
                                Swal.fire("Error", "Something went wrong!", "error");
                            });
                    }
                });
            });
        });
    });

    //Edit Client
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-edit-client").forEach(button => {
            button.addEventListener("click", function() {
                let clientId = this.getAttribute("data-id");
                let clientName = this.getAttribute("data-name");
                let clientRole = this.getAttribute("data-role");
                let description = this.getAttribute("data-description");
                let imagePath = this.getAttribute("data-image");

                Swal.fire({
                    title: "Edit Client",
                    width: 400,
                    padding: "1.2em",
                    background: "#f8f9fa",
                    confirmButtonColor: "#007bff",
                    cancelButtonColor: "#dc3545",
                    showCloseButton: true,
                    allowOutsideClick: false,
                    backdrop: true,
                    html: `
                        <div style="display: flex; flex-direction: column; align-items: center; width: 100%; box-sizing: border-box;">
                            <img src="${imagePath}" width="100" style="border-radius: 8px; margin-bottom: 10px;">
                            <label for="name" style="font-size: 14px; align-self: flex-start;">Client Name:</label>
                            <input type="text" id="name" class="swal2-input" style="width: 100%; font-size: 14px; box-sizing: border-box;" value="${clientName}">
                            <label for="role" style="font-size: 14px; align-self: flex-start;">Client Role:</label>
                            <input type="text" id="role" class="swal2-input" style="width: 100%; font-size: 14px; box-sizing: border-box;" value="${clientRole}">
                            <label for="description" style="font-size: 14px; align-self: flex-start;">Description:</label>
                            <textarea id="description" class="swal2-textarea" style="width: 100%; font-size: 14px; height: 100px; box-sizing: border-box;">${description}</textarea>
                            <label for="image" style="font-size: 14px; align-self: flex-start;">Image:</label>
                            <input type="file" id="image" class="swal2-file" style="width: 100%; font-size: 12px; box-sizing: border-box;">
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: "Update",
                    cancelButtonText: "Cancel",
                    preConfirm: () => {
                        let formData = new FormData();
                        formData.append("id", clientId);
                        formData.append("name", document.getElementById("name").value);
                        formData.append("role", document.getElementById("role").value);
                        formData.append("description", document.getElementById("description").value);
                        let imageFile = document.getElementById("image").files[0];
                        if (imageFile) {
                            formData.append("image", imageFile);
                        }

                        return fetch("edit_client.php", {
                                method: "POST",
                                body: formData
                            })
                            .then(response => response.text())
                            .then(data => {
                                if (data.trim() === "Success") {
                                    Swal.fire("Updated!", "The client has been updated.", "success")
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire("Error", "Something went wrong!", "error");
                                }
                            })
                            .catch(error => {
                                Swal.fire("Error", "Something went wrong!", "error");
                            });
                    }
                });
            });
        });
    });

    //Sort Booking

    function sortBookings() {
        var sortByValue = document.getElementById('sortByDropdown').value;
        window.location.href = window.location.pathname + "?sort=" + sortByValue;
    }
</script>


</html>