<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showcase | Mirror Your World </title>


    <link rel="stylesheet" href="Style/Admin_ShowcasePageCSS.css" />
    <link rel="stylesheet" href="Style/Required.css" />

</head>
<body>
    
   <!-- Required -->

   <div class="navbar">
        <a href="Admin_ShowcasePage.php">Showcase</a>
        <a href="Admin_AppointmentsPage.php">Appointments</a>
        <a href="Admin_ReviewsPage.php">Reviews</a>
        <a href="Admin_FAQPage.php">FAQ</a>
    </div>

   <div class="logo">
    <img src="Assets/icon_calendar.png" class="cal_img" alt="calendar" style="width: 30px" onclick="openCalModal()">
</div>

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
        <a href="#" onclick="showContent('active-bookings')">Works</a>
        <a href="#" onclick="showContent('past-bookings')">People We've Worked With</a>
    </div>

    <!-- Main Content (Forms to edit) -->
    <div class="content">

        <!-- Active Bookings -->
        <div class="section" id="active-bookings">
            <h2>Works</h2> 
            <!-- Yung 12 hours pagitan na pwede silamagcancel both client and user -->
            
            <table class = "sortby-container">
            <tr>
                <td> <img src = "Assets/icon_sortBy.png" class = "sortby-icon"> </td>
                <td> <h4> Sort by: Most Recent </h4> </td>
                
            </tr>
            </table>

                <!-- Review Container -->

            <table class = "booking-container"> 
            <tr>
                <td class = "td-date"> 
                <form action="upload.php" method="POST" enctype="multipart/form-data">
                <input type="file" class="fileUpload" name="fileUpload" id="fileUpload" accept="image/*" required>
                <br><br>
                <button type="submit" name="submit" style="background-color:green">Upload</button>
</form> </td>

                <td class = "td-details"> 
                    <h5> Company Name </h5>
                </td>

                <td class = "td-booker"> 
                    <h5> MM/DD/YY <br> </h5>
                </td>

                <td class = "td-review"> 
                    <h5> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt aliqua. 
                     </h5>
                    
                </td>
            </tr>

            <tr>

            <td class = "td-date">  </td>

            <td class = "td-details"> 
                <h6> Edit </h6>
            </td>

            <td class = "td-booker"> 
                <h6>  Edit </h6>
            </td>

            <td class = "td-review"> 
                <h6> Edit </h6>
            </td>  

            </tr>
            </table>  

             <!-- End Review Container -->
            

        </div>

        <!-- Past Bookings -->
        <div class="section" id="past-bookings" style="display: none;">
            <h2>People We've Worked With</h2>


        
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

   
</script>
    

</html>