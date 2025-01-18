<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment | Mirror Your World</title>

    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/User_FormsPageCSS.css" />
    

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
                <a href="User_ActiveBookings.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Active Bookings</a>
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
    <center> 
       
    <div class="BGhome-container">
        <img src="Assets/bg_HomePage.png" alt="Full-Screen Image" class="BGhome">
        <div class="txt_Tite">
            <br> <br> <br>
            <h2 class="txt_MYW"> Mirror Your World. </h2>
            <h4 class = "txt_Desc"> Appointment and Consultation </h4>
        </div>
    </div>

</center>

    <div class="form-container">
        <form>
            <div class="name-fields">
                <div class="input-wrapper1">
                    <label for="first-name">Full Name</label>
                    <input type="text" id="first-name" name="first-name" placeholder="First Name" required>
                </div>
                <div class="input-wrapper1">
                    <label for="last-name" style ="color: #f9f9f9"> - </label>
                    <input type="text" id="last-name" name="last-name" placeholder="Last Name" required>
                    
                </div>
            </div>
    
            <div class="input-wrapper">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"  placeholder="example@email.com" required>
                
            </div>
    
            <div class="consultation-fields">
                <div class="input-wrapper2">
                    <label for="consultation-type">Consultation Type</label>
                    <select id="consultation-type" name="consultation-type" placeholder="select" required>
                        <option value="" disabled selected style="color: gray">Select Type</option> 
                        <option value="aluminum">Aluminum</option>
                        <option value="glass">Glass</option>
                        <option value="aluminum-and-glass">Aluminum and Glass</option>
                    </select>
                    
                </div>
    
                <div class="input-wrapper2">
                    <label for="appointment-date">Date of Appointment</label>
                    <input type="date" id="appointment-date" name="appointment-date" required>
                  
                </div>
    
    
                    <div class="input-wrapper2">
                        <label for="time">Time of Appointment</label>
                        <select id="=time" name="time" placeholder="select" required>
                            <option value="" disabled selected>Select Time</option> 
                            <option value="aluminum">9:00 AM</option>
                            <option value="glass"> 12:00 PM </option>
                            <option value="aluminum-and-glass">3:00PM</option>
                            <option value="aluminum-and-glass">6:00PM</option>
                        </select>
                    </div>
            </div>

            <div class="request-field">
                <div class="input-wrapper">
                    <label for="request">Special Requests</label>
                    <input type="text" id="request" name="request" placeholder="e.g. Can you accommodate [ ] size?" required>
                    
                </div>
            </div>
    
            <div class="form-buttons">
                <button type="submit" class="btn-submit">Request Consultation</button>
                <button type="reset" class="btn-clear">Clear Form</button>
            </div>

            
        </form>
    </div>
    
    
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




     </script>



    
</body>
</html>