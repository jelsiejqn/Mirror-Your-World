<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs and Inquiries | Mirror Your World </title>


    <link rel="stylesheet" href="Style/Admin_FAQPageCSS.css" />
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
        <a href="Admin_FAQPage.php">FAQ</a>
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
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s; ">
                <a href="Admin_LoginPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Logout</a>
            </li>
        </ul>
    </div>

    <!-- Required -->


    <div class="dashboard-container">
        <!-- Sidebar (Options) -->
        <div class="sidebar">
            <h3>Dashboard</h3>
            <a href="#" onclick="showContent('active-bookings')">FAQs</a>
            <!-- <a href="#" onclick="showContent('past-bookings')">Services Offered</a> -->
            <a href="#" onclick="showContent('cancelled')">Available Materials </a>
            <a href="#" onclick="showContent('policies')">Policies </a>
        </div>

        <!-- Main Content (Forms to edit) -->
        <div class="content">


            <!-- FAQs -->
            <div class="section" id="active-bookings">
                <h2>FAQs</h2>
                <!-- Yung 12 hours pagitan na pwede silamagcancel both client and user -->

                <table class="sortby-container">
                    <tr>
                        <td> <img src="Assets/icon_sortBy.png" class="sortby-icon"> </td>
                        <td>
                            <h4> Sort by: All / Unanswered / Answered </h4>
                        </td>
                        <td>
                            <img src="Assets/icon_Add.png" class="add-icon-avail" onclick="openQuestionModal()">
                        </td>

                    </tr>





                </table>

                <center>

                    <table class="booking-container">
                        <tr>
                            <td class="td-date">
                                <h1> 1 </h1>
                            </td>

                            <td class="td-details">
                                <form>
                                    <center>
                                        <textarea name="company-name" class="company-name"> Question Here </textarea>
                                        <br> <button type="submit" name="update-company" class="update">Update</button>
                                </form>

                            </td>

                            <td class="td-booker">
                                <form>
                                    <center>
                                        <textarea name="company-name" class="company-name"> Answer Here </textarea>
                                        <br> <button type="submit" name="update-company" class="update">Update</button>
                                </form>
                            </td>
                </center>
                </td>

                <td class="td-buttons">
                    <button class="post-btn" onclick="openModal()">
                        <h5 class="txt-cancel"> Post </h5>
                    </button>
                </td>

                <td class="td-buttons">
                    <button class="delete-btn" onclick="openModal()">
                        <h5 class="txt-cancel"> Delete </h5>
                    </button>
                </td>


                <td class="td-details">
                    <h5> Status: <br> Answered </h5>

                </td>

                </tr>

                </table>

                <table class="booking-container">
                    <tr>
                        <td class="td-date">
                            <h1> 2 </h1>
                        </td>

                        <td class="td-details">
                            <form>
                                <center>
                                    <textarea name="company-name" class="company-name"> Question Here </textarea>
                                    <br> <button type="submit" name="update-company" class="update">Update</button>
                            </form>

                        </td>

                        <td class="td-booker">
                            <form>
                                <center>
                                    <textarea name="company-name" class="company-name"> Answer Here </textarea>
                                    <br> <button type="submit" name="update-company" class="update">Update</button>
                            </form>
                        </td>
                        </`center>
                        </td>

                        <td class="td-buttons">
                            <button class="post-btn" onclick="openModal()">
                                <h5 class="txt-cancel"> Post </h5>
                            </button>
                        </td>

                        <td class="td-buttons">
                            <button class="delete-btn" onclick="openModal()">
                                <h5 class="txt-cancel"> Delete </h5>
                            </button>
                        </td>


                        <td class="td-details">
                            <h5> Status: <br> Unanswered </h5>

                        </td>

                    </tr>

                </table>


            </div>



            <!-- Available Materials -->
            <div class="section" id="cancelled" style="display: none;">
                <h2>Available Materials</h2>



                <table class="sortby-container">
                    <tr>
                        <td> <img src="Assets/icon_sortBy.png" class="sortby-icon"> </td>
                        <td>
                            <h4> Sort by: All / Available / Unavailable </h4>
                        </td>

                        <td>
                            <img src="Assets/icon_Add.png" class="add-icon-avail" onclick="openModal()">
                        </td>


                        <!-- Modal Here -->

                    </tr>
                </table>

                <center>


                    <table class="booking-container">
                        <tr>
                            <td class="td-date">
                                <form action="upload.php" method="POST" enctype="multipart/form-data">
                                    <input type="file" class="fileUpload" name="fileUpload" id="fileUpload" accept="image/*">
                                    <button type="submit" name="submit" class="update">Upload</button>
                            </td>



                            <td class="td1">

                                <form>
                                    <center>
                                        <textarea name="company-name" class="company-name"> Material Name </textarea>
                                        <br> <button type="submit" name="update-company" class="update">Update</button>
                                </form>

                            </td>

                            <td class="td-buttons">

                                <center>
                                    <label for="availability-avail">Availability:</label>
                                    <select id="availability-avail" name="availability-avail" required form-control>
                                        <option value="inStock">In Stock</option>
                                        <option value="outOfStock">Out of Stock</option>
                                    </select><br><br>
                            </td>

                            <td class="td-buttons1">
                                <center>
                                    <button class="post-btn" onclick="openModal()">
                                        <h5 class="txt-cancel"> Edit </h5>
                                    </button>
                            </td>

                            <td class="td-buttons1">
                                <center>
                                    <button class="delete-btn" onclick="openModal()">
                                        <h5 class="txt-cancel"> Delete </h5>
                                    </button>
                            </td>



                        </tr>
                    </table>

            </div>

            <!-- Policies -->
            <div class="section" id="policies" style="display: none;">
                <h2>Policies</h2>


                <table class="sortby-container">
                    <tr>
                        <td> <img src="Assets/icon_sortBy.png" class="sortby-icon"> </td>
                        <td>
                            <h4> Sort by: Most Recent </h4>
                        </td>

                        <td>
                            <img src="Assets/icon_Add.png" class="add-icon-avail" onclick="openPolicyModal()">
                        </td>

                    </tr>
                </table>

                <!-- Modal structure Policy-->

                <center>

                    <table class="booking-container">
                        <tr>
                            <td class="td-date">
                                <h1> 1 </h1>
                            </td>

                            <td class="td-details">
                                <form>
                                    <center>
                                        <textarea name="company-name" class="company-name"> Policy Title Here </textarea>
                                        <br> <button type="submit" name="update-company" class="update">Update</button>
                                </form>

                            </td>

                            <td class="td-booker">
                                <form>
                                    <center>
                                        <textarea name="company-name" class="company-name"> Policy Desc Here </textarea>
                                        <br> <button type="submit" name="update-company" class="update">Update</button>
                                </form>
                            </td>
                </center>
                </td>

                <td class="td-buttons">
                    <button class="post-btn" onclick="openModal()">
                        <h5 class="txt-cancel"> Post </h5>
                    </button>
                </td>

                <td class="td-buttons">
                    <button class="delete-btn" onclick="openModal()">
                        <h5 class="txt-cancel"> Delete </h5>
                    </button>
                </td>


                </tr>

                </table>

            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="addProductModal-avail" class="modal-avail">
        <div class="modal-content-avail">
            <span class="close-avail" onclick="closeModal()">&times;</span>
            <h2>Add Materials</h2>
            <form>
                <label for="picture-avail">Picture:</label>
                <input type="file" id="picture-avail" name="picture-avail" required form-control><br><br>

                <label for="materialName-avail">Material Name:</label>
                <input type="text" id="materialName-avail" name="materialName-avail" required form-control><br><br>

                <label for="productDetail-avail">Product Detail:</label>
                <textarea id="productDetail-avail" name="productDetail-avail" required form-control></textarea><br><br>

                <label for="productBenefits-avail">Product Benefits:</label>
                <textarea id="productBenefits-avail" name="productBenefits-avail" required form-control></textarea><br><br>

                <label for="productPrice-avail">Product Price:</label>
                <input type="number" id="productPrice-avail" name="productPrice-avail" required form-control><br><br>

                <label for="availability-avail">Availability:</label>
                <select id="availability-avail" name="availability-avail" required form-control>
                    <option value="inStock">In Stock</option>
                    <option value="outOfStock">Out of Stock</option>
                </select><br><br>

                <center>
                    <button type="submit" class="submit-btn-avail">Submit</button>
            </form>
        </div>
    </div>

    <!-- Modal structure Policy-->
    <div id="modal-policy" class="modal-policy">
        <div class="modal-content-policy">
            <span class="close-policy" onclick="closePolicyModal()">&times;</span>
            <h2>Enter Policy Information</h2>

            <label for="policy-title">Policy Title:</label>
            <input type="text" id="policy-title" class="policy-input" placeholder="Enter policy title" />

            <label for="policy-description">Policy Description:</label>
            <textarea id="policy-description" class="policy-input" placeholder="Enter policy description"></textarea>

            <div class="modal-buttons">
                <button class="btn-policy" onclick="addPolicy()">Add</button>
                <button class="btn-policy" onclick="closePolicyModal()">Cancel</button>
            </div>
        </div>
    </div>

    <div class="modal-bg">
        <!-- Modal Structure Question -->
        <div id="questionModal" class="modal-question">
            <div class="modal-content-question">
                <h3>Ask a Question</h3>
                <label for="question-input">Question:</label>
                <input type="text" id="question-input" class="input-question" placeholder="Enter your question here" required><br><br>

                <label for="answer-input">Answer:</label>
                <input type="text" id="answer-input" class="input-question" placeholder="Enter your answer here" required><br><br>

                <button class="add-button-question" onclick="addQuestion()">Add</button>
                <button class="cancel-button-question" onclick="closeQuestionModal()">Cancel</button>
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

    function openModal() {
        document.getElementById("addProductModal-avail").style.display = "block";
    }

    function closeModal() {
        document.getElementById("addProductModal-avail").style.display = "none";
    }

    // Close the modal if clicked outside the modal content
    window.onclick = function(event) {
        if (event.target === document.getElementById("addProductModal-avail")) {
            closeModal();
        }
    }


    // Function to open the modal
    function openQuestionModal() {
        document.getElementById("questionModal").style.display = "block";
    }

    // Function to close the modal
    function closeQuestionModal() {
        document.getElementById("questionModal").style.display = "none";
    }

    // Function to handle the "Add" button
    function addQuestion() {
        const question = document.getElementById("question-input").value;
        const answer = document.getElementById("answer-input").value;

        if (question && answer) {
            // You can handle the question and answer here, e.g., log it or store it
            console.log("Question: " + question);
            console.log("Answer: " + answer);

            // Close the modal after adding
            closeQuestionModal();

            // Optionally, clear the inputs
            document.getElementById("question-input").value = "";
            document.getElementById("answer-input").value = "";
        } else {
            alert("Please enter both a question and an answer.");
        }
    }

    // Close the modal if the user clicks outside of the modal content
    window.onclick = function(event) {
        if (event.target == document.getElementById("questionModal")) {
            closeQuestionModal();
        }
    }

    // Open Modal Function
    function openPolicyModal() {
        document.getElementById('modal-policy').style.display = 'block';
    }

    // Close Modal Function
    function closePolicyModal() {
        document.getElementById('modal-policy').style.display = 'none';
    }

    // Add Policy Function (for demonstration purposes, you can modify as needed)
    function addPolicy() {
        const policyTitle = document.getElementById('policy-title').value;
        const policyDescription = document.getElementById('policy-description').value;

        // Handle the data (e.g., send to server or store)
        console.log('Policy Title:', policyTitle);
        console.log('Policy Description:', policyDescription);

        // Close the modal after adding
        closePolicyModal();
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
</script>


</html>