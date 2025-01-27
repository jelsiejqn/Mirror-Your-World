<?php
session_start();
// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showcase | Mirror Your World</title>

    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/User_ShowcaseCSS.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    

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
            <?php if ($isLoggedIn): ?>
                <li style="list-style: none; margin: 0; padding: 10px;">
                    <a href="User_AccountPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">View Profile</a>
                </li>
                <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s; ">
                    <a href="User_ActiveBookings.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">My Appointments</a>
                </li>
                
                <form method="POST" action="User_Homepage.php">
                <li style="list-style: none; margin: 0; padding: 10px;">
                    <a href="User_LogoutProcess.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Logout</a>
                </li>
                </form>
            <?php else: ?>
                <li style="list-style: none; margin: 0; padding: 10px;">
                    <a href="User_LoginPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Login</a>
                </li>
                <li style="list-style: none; margin: 0; padding: 10px;">
                    <a href="User_SignupPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Sign Up</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <a href="User_InquiryPage.php">
        <img src="Assets/icon_FAQ.png" alt="Inquiry Icon" class="inquiry-icon" />
    </a>

    
    <div class="BGhome-container">

        <div class="text-container">
            <h1>Mirror Your <br>World Showcase</h1>
            <p>Client reviews, past works,  <br> and people we've worked with.</p>


          
        </div>
    
        <img src="Assets/bg_HomePage.png" alt="Full-Screen Image" class="BGhome">
    </div>

    <!-- Required -->

    <a href="#" class="back-to-top" title="Go back to top"> 
    <img src="Assets/icon_uparrow.png" class ="uparrow">
    <span class="tooltip">Go back to top</span>
</a>



    <!-- Page 2 -->

    <div class="PageTwo-container">

        <center> 
        <div class="logoHeader-container">
        <img src = "Assets/icon_Logo.png" class = "logoHeader"> 
        </div>

        <div class = "navbar-2"> 
            <table>
                <tr>
                    <td> <a href="#section-works" class ="navbar-link"> Works </a> </td>
                    <td> <a href="#section-clients" class ="navbar-link"> Clients </a> </td>
                    <td> <a href="#section-reviews" class ="navbar-link" id="section-works"> Reviews </a> </td>
                </tr>
                
            </table>
        </div>


        <h2 class="works-title"> Works </h2>
<center> 

<div class="frames-container">
    
    <div class="frame" id="frame1">
        <img src="Assets/placeholder.jpg" alt="Icon 1" class="frame-icon">
   
        <p class="frame-description"> Company Name </p>
        <p class="frame-date"> mm/yy/dd </p>
        <p class="frame-info"> short description here. </p>
      
    </div>
    
    <div class="frame" id="frame1">
        <img src="Assets/placeholder1.jpg" alt="Icon 1" class="frame-icon">
   
        <p class="frame-description"> Company Name </p>
        <p class="frame-date"> mm/yy/dd </p>
        <p class="frame-info"> short description here. </p>
      
    </div>

    <div class="frame" id="frame1">
        <img src="Assets/placeholder2.jpg" alt="Icon 1" class="frame-icon">
   
        <p class="frame-description"> Company Name </p>
        <p class="frame-date"> mm/yy/dd </p>
        <p class="frame-info"> short description here. </p>
      
    </div>

    <div class="frame" id="frame1">
        <img src="Assets/placeholder2.jpg" alt="Icon 1" class="frame-icon">
   
        <p class="frame-description"> Company Name </p>
        <p class="frame-date"> mm/yy/dd </p>
        <p class="frame-info"> short description here. </p>
      
    </div>
    
    <div class="frame" id="frame1">
        <img src="Assets/placeholder2.jpg" alt="Icon 1" class="frame-icon">
   
        <p class="frame-description"> Company Name </p>
        <p class="frame-date"> mm/yy/dd </p>
        <p class="frame-info"> short description here. </p>
      
    </div>

    <div class="frame" id="frame1">
        <img src="Assets/placeholder2.jpg" alt="Icon 1" class="frame-icon">
   
        <p class="frame-description"> Company Name </p>
        <p class="frame-date"> mm/yy/dd </p>
        <p class="frame-info"> short description here. </p>
      
    </div>


</div>


<div class="material-modal-overlay" id="material-modal">
<div class="material-modal">
<button class="material-modal-close" onclick="closeMaterialModal()">&times;</button>
<div class="material-modal-left">
<img src="Assets/placeholder2.jpg">
<div id="material-modal-status" class="material-modal-status">mm/dd/yyyy</div>
</div>
<div class="material-modal-right">
<div id="material-modal-title" class="material-modal-right-title">Title Here (pero yung variable)</div>
<div id="material-modal-detail1" class="material-modal-details">Detail 1: Information</div>
<div id="material-modal-detail2" class="material-modal-details">Detail 2: Information</div>
<div id="material-modal-detail3" class="material-modal-details">Detail 3: Information</div>
<div id="material-modal-detail4" class="material-modal-details">Detail 4: Information</div>
</div>
</div>
</div>


        
          <div class="faq-modal" id="faq-modal" style="display: none;">
            <div class="faq-modal-content">
              <span class="faq-close" id="faq-close">&times;</span>
              <h2 id="faq-modal-title">Title</h2>
              <p id="faq-modal-description">Description</p>
            </div>
          </div>

      
</center> 

<h2 class="clients-title" id ="section-clients"> Prominent Figures We've Worked With </h2>

<div class = "clients-container">
<div class = "wrapper2">
  
<div class="card">
    <img src="Assets/client1.jpg" />
    <div class = "card-descriptions">
    <h2 class="card-title">Client Name</h2>
    <h3 class="card-subtitle">Property Owner</h3>
    <br>
    <h4 class="card-info"> Short description about the work or job done for the client. </h4>
    </div>
</div>

<div class="card">
    <img src="Assets/client2.jpg" />
    <div class = "card-descriptions">
    <h2 class="card-title">Client Name</h2>
    <h3 class="card-subtitle">Property Owner</h3>
    <br>
    <h4 class="card-info"> Short description about the work or job done for the client. </h4>
    </div>
</div>

<div class="card">
    <img src="Assets/client3.jpg" />
    <div class = "card-descriptions">
    <h2 class="card-title">Client Name</h2>
    <h3 class="card-subtitle">Property Owner</h3>
    <br>
    <h4 class="card-info"> Short description about the work or job done for the client. </h4>
    </div>
</div>

<div class="card">
    <img src="Assets/client4.jpg" />
    <div class = "card-descriptions">
    <h2 class="card-title">Client Name</h2>
    <h3 class="card-subtitle">Property Owner</h3>
    <br>
    <h4 class="card-info"> Short description about the work or job done for the client. </h4>
    </div>
</div>


</div>
</div>





<h2 class="reviews-title" id ="section-reviews"> Client Reviews </h2>

<div class = "reviews-container">

<figure class="snip1157">
  <blockquote>Calvin: You know sometimes when I'm talking, my words can't keep up with my thoughts... I wonder why we think faster than we speak. Hobbes: Probably so we can think twice.
    <div class="arrow"></div>
  </blockquote>
  <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sq-sample3.jpg" alt="sq-sample3" />
  <div class="author">
  <h5>Max Conversion<span> Property Owner </span></h5>
  </div>
</figure>


<figure class="snip1157 hover">
  <blockquote>Thank you. before I begin, I'd like everyone to notice that my report is in a professional, clear plastic binder...When a report looks this good, you know it'll get an A. That's a tip kids. Write it down.
    <div class="arrow"></div>
  </blockquote>
  <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sq-sample27.jpg" alt="sq-sample27" />
  <div class="author">
    <h5>Max Conversion<span> Property Owner </span></h5>
  </div>
</figure>


<figure class="snip1157">
  <blockquote>My behaviour is addictive functioning in a disease process of toxic co-dependency. I need holistic healing and wellness before I'll accept any responsibility for my actions.
    <div class="arrow"></div>
  </blockquote>
  <img src="Assets/client1.jpg" alt="sq-sample17" />
  <div class="author">
    <h5>Eleanor Faint<span> Property Owner </span></h5>
  </div>
</figure>

<figure class="snip1157">
  <blockquote>My behaviour is addictive functioning in a disease process of toxic co-dependency. I need holistic healing and wellness before I'll accept any responsibility for my actions.
    <div class="arrow"></div>
  </blockquote>
  <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sq-sample17.jpg" alt="sq-sample17" />
  <div class="author">
    <h5>Eleanor Faint<span> Property Owner </span></h5>
  </div>
</figure>

<figure class="snip1157">
  <blockquote>My behaviour is addictive functioning in a disease process of toxic co-dependency. I need holistic healing and wellness before I'll accept any responsibility for my actions.
    <div class="arrow"></div>
  </blockquote>
  <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sq-sample17.jpg" alt="sq-sample17" />
  <div class="author">
    <h5>Eleanor Faint<span> Property Owner </span></h5>
  </div>
</figure>

<figure class="snip1157">
  <blockquote>My behaviour is addictive functioning in a disease process of toxic co-dependency. I need holistic healing and wellness before I'll accept any responsibility for my actions.
    <div class="arrow"></div>
  </blockquote>
  <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sq-sample17.jpg" alt="sq-sample17" />
  <div class="author">
    <h5>Eleanor Faint<span> Property Owner </span></h5>
  </div>
</figure>

</div>



    </center>

    
      
    </div>
</div>


    <div class="Footer-container">

        <div class="text-container">
            <h1>Book a <br> consultation <br> now.</h1>
            <p>Still got questions? <a href="#"> Email us. </a> </p>
            <button class="btnConsult" onclick="showConsultAlert()">Consult</button>
        </div>
    
        <img src="Assets/bg_Footer.png" alt="Full-Screen Image" class="BGhome">
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

// Get elements
const faqFrames = document.querySelectorAll('.faq-frame');
const faqModal = document.getElementById('faq-modal');
const faqClose = document.getElementById('faq-close');
const faqModalTitle = document.getElementById('faq-modal-title');
const faqModalDescription = document.getElementById('faq-modal-description');

// Data for modal content
const faqData = {
  "faq-frame-1": { title: "FAQ Title 1", description: "This is the description for FAQ 1." },
  "faq-frame-2": { title: "FAQ Title 2", description: "This is the description for FAQ 2." },
  "faq-frame-3": { title: "FAQ Title 3", description: "This is the description for FAQ 3." },
  "faq-frame-4": { title: "FAQ Title 4", description: "This is the description for FAQ 4." },
};

// Add click event to each FAQ frame
faqFrames.forEach(frame => {
  frame.addEventListener('click', function() {
    const frameId = this.id;
    const { title, description } = faqData[frameId];
    faqModalTitle.textContent = title;
    faqModalDescription.textContent = description;
    faqModal.style.display = 'flex';
  });
});

// Close modal
faqClose.addEventListener('click', () => {
  faqModal.style.display = 'none';
});

// Close modal when clicking outside the modal content
window.addEventListener('click', (event) => {
  if (event.target === faqModal) {
    faqModal.style.display = 'none';
  }
});


function openMaterialModal(title, subtitle, description, status, detail1, detail2, detail3, detail4) {
        document.getElementById('material-modal-title').textContent = title;
        document.getElementById('material-modal-status').textContent = status;
        document.getElementById('material-modal-detail1').textContent = `Product Details: ${detail1}`;
        document.getElementById('material-modal-detail2').textContent = `Application: ${detail2}`;
        document.getElementById('material-modal-detail3').textContent = `Benefits: ${detail3}`;
        document.getElementById('material-modal-detail4').textContent = `Base Price: ${detail4}`;
        document.getElementById('material-modal').style.display = 'flex';
    }

    function closeMaterialModal() {
        document.getElementById('material-modal').style.display = 'none';
    }


    //  Frame and Modal

function openModal(frameId) {
    // Select the modal and its content areas
    const modal = document.getElementById('modal');
    const modalImage = document.getElementById('modal-image');
    const modalTitle = document.getElementById('modal-title');
    const modalDescription = document.getElementById('modal-description');

    // Set the image, title, and description based on the clicked frame
    if (frameId === 1) {
        modalImage.src = 'Assets/image1.jpg'; // Replace with actual image URL
        modalTitle.innerHTML = 'Detailed Frame 1 Title';
        modalDescription.innerHTML = 'Detailed description of the content for frame 1 will go here.';
    } else if (frameId === 2) {
        modalImage.src = 'Assets/image2.jpg'; // Replace with actual image URL
        modalTitle.innerHTML = 'Detailed Frame 2 Title';
        modalDescription.innerHTML = 'Detailed description of the content for frame 2 will go here.';
    } else if (frameId === 3) {
        modalImage.src = 'Assets/image3.jpg'; // Replace with actual image URL
        modalTitle.innerHTML = 'Detailed Frame 3 Title';
        modalDescription.innerHTML = 'Detailed description of the content for frame 3 will go here.';
    }

    // Display the modal
    modal.style.display = 'block';
}

function closeModal() {
    // Close the modal by setting its display to none
    const modal = document.getElementById('modal');
    modal.style.display = 'none';
}

// Close modal if the user clicks outside the modal content
window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target === modal) {
        closeModal();
    }
}

function showConsultAlert() {
    Swal.fire({
        title: 'Are we the perfect fit for you?',
        text: 'Please visit the FAQs page before proceeding!',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Proceed to Form',
        cancelButtonText: 'Visit FAQs',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            content: 'custom-swal-text',
            confirmButton: 'custom-swal-confirm',
            cancelButton: 'custom-swal-cancel'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to the form page
            window.location.href = 'User_FormsPage.php';
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Redirect to the FAQs page
            window.location.href = 'User_InquiryPage.php';
        }
    });
}

function showConsultAlert() {
    // Check if the user is logged in
    <?php if ($isLoggedIn): ?>
        Swal.fire({
            title: 'Are we the perfect fit for you?',
            text: 'Please visit the FAQs page before proceeding!',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Proceed to Form',
            cancelButtonText: 'Visit FAQs',
            reverseButtons: true,
            customClass: {
                popup: 'custom-swal-popup',
                title: 'custom-swal-title',
                content: 'custom-swal-text',
                confirmButton: 'custom-swal-confirm',
                cancelButton: 'custom-swal-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'User_FormsPage.php';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = 'User_InquiryPage.php';
            }
        });
    <?php else: ?>
        Swal.fire({
            title: 'Please Log In or Sign Up first',
            text: 'You need to be logged in to proceed with the consultation.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Log In',
            cancelButtonText: 'Sign Up',
            reverseButtons: true,
            customClass: {
                popup: 'custom-swal-popup',
                title: 'custom-swal-title',
                content: 'custom-swal-text',
                confirmButton: 'custom-swal-confirm',
                cancelButton: 'custom-swal-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'User_LoginPage.php';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = 'User_SignupPage.php'; // Redirect to signup page
            }
        });
    <?php endif; ?>
}
</script>
</html>