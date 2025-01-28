<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Bookings | Mirror Your World </title>


    <link rel="stylesheet" href="Style/User_ActiveBookingsCSS.css" />
    <link rel="stylesheet" href="Style/Required.css" />

</head>
<body>
    
   <!-- Required -->

   <div class="navbar">
        <a href="User_Homepage.php">About</a>
        <a href="User_Contactpage.php">Contact</a>
        <a href="User_Showcase.php">Showcase</a>
    </div>

    <div class="logo">
          <img src="Assets/icon_Logo.png" alt="Logo" style="width: 30px"> 
    </div>

    <div class="profile-container" style="position: fixed; top: 10px; right: 20px; z-index: 1000; border-radius: 20px;">
        <button class="btn dropdown-trigger" data-target="dropdown1" style=" border-radius: 20px; padding: 0; background-color: transparent; border: none; cursor: pointer;" onclick="toggleDropdown()">
            <img src="Assets/icon_Profile.png" class="iconProfile" alt="Profile Icon" width="40px" height="40px" style="width: 25px; height: 25px; object-fit: cover; cursor: pointer; transition: filter 0.3s ease;" onmouseover="this.style.filter='invert(1)';" onmouseout="this.style.filter='invert(0)';" />
        </button>
        
        <br />
        <ul id="dropdown1" class="dropdown-content" style="transition: 0.3s; display: none; position: absolute; top: 60px; right: 0; background-color: white; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15); width: 200px; padding: 0; margin: 0;">
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                <a href="User_AccountPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Account</a>
            </li>
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s; ">
                <a href="User_ActiveBookings.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">My Appointments</a>
            </li>
            <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s; ">
                <a href="User_Homepage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Logout</a>
            </li>
        </ul>
    </div>

    <a href="User_InquiryPage.php">
        <img src="Assets/icon_FAQ.png" alt="Inquiry Icon" class="inquiry-icon" />
    </a>

    <!-- Required -->


    <div class="dashboard-container">
    <!-- Sidebar (Options) -->
    <div class="sidebar">
        <h3>My Appointments</h3>
        <a href="#" onclick="showContent('active-bookings')">Active Bookings</a>
        <a href="#" onclick="showContent('past-bookings')">Past Bookings</a>
        <a href="#" onclick="showContent('cancelled')">Cancelled</a>
    </div>

    <!-- Main Content (Forms to edit) -->
    <div class="content">

        <!-- Active Bookings -->
        <div class="section" id="active-bookings">
            <h2>Active Bookings</h2>
            
            <table class = "sortby-container">
            <tr>
                <td> <img src = "Assets/icon_sortBy.png" class = "sortby-icon"> </td>
                <td> <h4> Sort by: Most Recent </h4> </td>
                
            </tr>
            </table>

            <table class = "booking-container"> 
            <tr>
                <td class = "td-date"> <h1> Jan 20 2025</h1> </td>

                <td class = "td-details"> 
                    <h5> Consultation Type: Glass  </h5>
                    <h5> Time of Appointment: 3PM  </h5>
                    <h5> Site of Appointment: Makati  </h5>
                </td>

                <td class = "td-booker"> 
                    <h5> Name: Dionne Blacer  </h5>
                    <h5> Email: hello@gmail.com  </h5>
                    <h5> Contact Number: 09153628520  </h5>
                </td>

                <td class = "td-buttons"> 
                    <button class = "cancel-btn"> <h5 class = "txt-cancel"> Cancel </h5> </button>
                </td>
                
            </tr>
            </table>  

            
            
            <table class = "booking-container"> 
            <tr>
                <td class = "td-date"> <h1> Jan 20 2025</h1> </td>

                <td class = "td-details"> 
                    <h5> Consultation Type: Glass  </h5>
                    <h5> Time of Appointment: 3PM  </h5>
                    <h5> Site of Appointment: Makati  </h5>
                </td>

                <td class = "td-booker"> 
                    <h5> Name: Dionne Blacer  </h5>
                    <h5> Email: hello@gmail.com  </h5>
                    <h5> Contact Number: 09153628520  </h5>
                </td>

                <td class = "td-buttons"> 
                    <button class = "cancel-btn"> <h5 class = "txt-cancel"> Cancel </h5> </button>
                </td>
                
            </tr>
            </table>       
            
            <table class = "booking-container"> 
            <tr>
                <td class = "td-date"> <h1> Jan 20 2025</h1> </td>

                <td class = "td-details"> 
                    <h5> Consultation Type: Glass  </h5>
                    <h5> Time of Appointment: 3PM  </h5>
                    <h5> Site of Appointment: Makati  </h5>
                </td>

                <td class = "td-booker"> 
                    <h5> Name: Dionne Blacer  </h5>
                    <h5> Email: hello@gmail.com  </h5>
                    <h5> Contact Number: 09153628520  </h5>
                </td>

                <td class = "td-buttons"> 
                    <button class = "cancel-btn"> <h5 class = "txt-cancel"> Cancel </h5> </button>
                </td>
                
            </tr>
            </table>       

            <table class = "booking-container"> 
            <tr>
                <td class = "td-date"> <h1> Jan 20 2025</h1> </td>

                <td class = "td-details"> 
                    <h5> Consultation Type: Glass  </h5>
                    <h5> Time of Appointment: 3PM  </h5>
                    <h5> Site of Appointment: Makati  </h5>
                </td>

                <td class = "td-booker"> 
                    <h5> Name: Dionne Blacer  </h5>
                    <h5> Email: hello@gmail.com  </h5>
                    <h5> Contact Number: 09153628520  </h5>
                </td>

                <td class = "td-buttons"> 
                    <button class = "cancel-btn"> <h5 class = "txt-cancel"> Cancel </h5> </button>
                </td>
                
            </tr>
            </table>          

            <table class = "booking-container"> 
            <tr>
                <td class = "td-date"> <h1> Jan 20 2025</h1> </td>

                <td class = "td-details"> 
                    <h5> Consultation Type: Glass  </h5>
                    <h5> Time of Appointment: 3PM  </h5>
                    <h5> Site of Appointment: Makati  </h5>
                </td>

                <td class = "td-booker"> 
                    <h5> Name: Dionne Blacer  </h5>
                    <h5> Email: hello@gmail.com  </h5>
                    <h5> Contact Number: 09153628520  </h5>
                </td>

                <td class = "td-buttons"> 
                    <button class = "cancel-btn"> <h5 class = "txt-cancel"> Cancel </h5> </button>
                </td>
                
            </tr>
            </table>          

            

        </div>

        <!-- Past Bookings -->
        <div class="section" id="past-bookings" style="display: none;">
            <h2>Past Bookings</h2>


            <table class = "sortby-container">
            <tr>
                <td> <img src = "Assets/icon_sortBy.png" class = "sortby-icon"> </td>
                <td> <h4> Sort by: Most Recent </h4> </td>
                
            </tr>
            </table>

            <table class = "booking-container"> 
            <tr>
                <td class = "td-date"> <h1> Jan 20 2025</h1> </td>

                <td class = "td-details"> 
                    <h5> Consultation Type: Glass  </h5>
                    <h5> Time of Appointment: 3PM  </h5>
                    <h5> Site of Appointment: Makati  </h5>
                </td>

                <td class = "td-booker"> 
                    <h5> Name: Dionne Blacer  </h5>
                    <h5> Email: hello@gmail.com  </h5>
                    <h5> Contact Number: 09153628520  </h5>
                </td>

                <td class = "td-buttons"> 
                    <img src ="Assets/icon_Check.png" class = "completed-icon">
                </td>
                
            </tr>
            </table>

        </div>

        <!-- Cancelled -->
        <div class="section" id="cancelled" style="display: none;">
            <h2>Cancelled</h2>
    

            <table class = "sortby-container">
            <tr>
                <td> <img src = "Assets/icon_sortBy.png" class = "sortby-icon"> </td>
                <td> <h4> Sort by: Most Recent </h4> </td>
                
            </tr>
            </table>

            <table class = "booking-container"> 
            <tr>
                <td class = "td-date"> <h1> Jan 20 2025 </h1> </td>

                <td class = "td-details"> 
                    <h5> Consultation Type: Glass  </h5>
                    <h5> Time of Appointment: 3PM  </h5>
                    <h5> Site of Appointment: Makati  </h5>
                </td>

                <td class = "td-booker"> 
                    <h5> Reason of Cancellation </h5>
                    <h5> Reason Here </h5>
                </td>

                <td class = "td-buttons"> 
                <img src ="Assets/icon_X.png" class = "completed-icon">
                </td>
                
            </tr>
            </table>

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

   
</script>
    

</html>