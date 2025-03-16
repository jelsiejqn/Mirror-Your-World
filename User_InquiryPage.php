<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ and Inquiry | Mirror Your World</title>

    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/User_InquiryPageCSS.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

     <!-- Required -->

     <div class="navbar">
        <a href="User_Homepage.php">About</a>
        <a href=User_InquiryPage.php">FAQ</a>
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
                <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                    <a href="User_AccountPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">View Profile</a>
                </li>
                <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                    <a href="User_ActiveBookings.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">My Appointments</a>
                </li>
                <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                    <a href="User_LogoutProcess.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Logout</a>
                </li>
            <?php else: ?>
                <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                    <a href="User_LoginPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Login</a>
                </li>
                <li style="list-style: none; margin: 0; padding: 10px; transition: 0.3s;">
                    <a href="User_SignupPage.php" style="color: black; text-decoration: none; display: block; padding: 5px 10px;">Sign Up</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    
    <div class="BGhome-container">

        <div class="text-container">
            <h1>Are we the <br>perfect fit <br> for you?</h1>
            <p>Frequently asked questions and <br /> inquiry page.</p>

            <h2> Still have questions? <br> Don't hesitate to email us!</h2>
            <button class="btn-email"> Email </button>
        </div>
    
        <img src="Assets/bg_HomePage.png" alt="Full-Screen Image" class="BGhome">
    </div>

    <!-- Required -->

    <a href="#" title="Go back to top" class ="uparrow2"> 
    <img src="Assets/icon_uparrow.png" class ="uparrow3">
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
                    <td> <a href="#section-faq" class ="navbar-link"> FAQS </a> </td>
                    <td> <a href="#section-services" class ="navbar-link"> Services Offered </a> </td>
                    <td> <a href="#section-materials" class ="navbar-link"> Available Materials </a> </td>
                    <td id ="section-faq"> <a href="#section-policies" class ="navbar-link"> Policies </a> </td>
                </tr>
            </table>
        </div>

        <div class="faq-container">
            <div class="faq-frame" id="faq-frame-1">
              <p>How do I book an appointment?</p>
            </div>
            <div class="faq-frame" id="faq-frame-2">
              <p>Do you accept custom designs?</p>
            </div>
            <div class="faq-frame" id="faq-frame-3">
              <p>Do you offer warranties?</p>
            </div>
            <div class="faq-frame" id="faq-frame-4">
              <p>What services do you provide?</p>
            </div>
            <div class="faq-frame" id="faq-frame-5">
                <p>Which areas do the company serve?</p>
            </div>
            <div class="faq-frame" id="faq-frame-6">
                <p>Do you offer consultations?</p>
            </div>
            <div class="faq-frame" id="faq-frame-7">
                <p>What is your cancellation policy?</p>
            </div>
            <div class="faq-frame" id="faq-frame-8">
                <p>What are the modes and terms of payment?</p>
            </div>
            <div class="faq-frame" id="faq-frame-9">
                <p>What should I do to prepare for the installation?</p>
            </div>
            <div class="faq-frame" id="faq-frame-10">
                <p>How long do the installations take?</p>
            </div>
            <div class="faq-frame" id="faq-frame-11">
                <p>What is the process after booking an appointment?</p>
            </div>
        </div>
        
        <div class="faq-modal" id="faq-modal" style="display: none;">
            <div class="faq-modal-content">
              <span class="faq-close" id="faq-close">&times;</span>
              <h2 id="faq-modal-title">Title</h2>
              <p id="faq-modal-description">Description</p>
            </div>
        </div>

        <div class="txtServices-container" id="section-services"> 
            <h2 class="txtServices"> Services Offered </h2>
        </div>

        <div class="services-container">
            <div class="frames-container">
                <!-- Frame 1 -->
                <div class="frame" id="frame1">
                    <img src="Assets/icon_Aluminum.png" alt="Icon 1" class="frame-icon">
                    <h3 class="frame-title">Aluminum Installation</h3>
                    <p class="frame-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <button class="learn-more-btn" onclick="openModal(1)">Learn More</button>
                </div>
            
                <!-- Frame 2 -->
                <div class="frame" id="frame2">
                    <img src="Assets/icon_Glass.png" alt="Icon 2" class="frame-icon">
                    <h3 class="frame-title">Glass Installation</h3>
                    <p class="frame-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <button class="learn-more-btn" onclick="openModal(2)">Learn More</button>
                </div>
            
                <!-- Frame 3 -->
                <div class="frame" id="frame3">
                    <img src="Assets/icon_GlassandAluminum.png" alt="Icon 3" class="frame-icon">
                    <h3 class="frame-title">Aluminum and Glass <br> Installation</h3>
                    <p class="frame-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <button class="learn-more-btn" onclick="openModal(3)">Learn More</button>
                </div>
            </div>
            
            <!-- Modal -->
            <div id="modal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="closeModal()">&times;</span>
                    <div class="modal-body">
                        <div id="modal-left" class="modal-left">
                            <img src="" alt="Image" id="modal-image">
                        </div>
                        <div id="modal-right" class="modal-right">
                            <h3 id="modal-title">Title</h3>
                            <p id="modal-description">Detailed description of the content will go here.</p>
                        </div>
                    </div>
                </div>
            </div>

            <center> 
            <h2 class="materials-title" id="section-materials"> Available Materials </h2>

            <div class="material-scroll-container">
                <div class="material-frame" onclick="openMaterialModal('Sample Title', 'Sample Subtitle', 'Sample Description', 'Available', 'Detail 1', 'Detail 2', 'Detail 3', 'Detail 4')">
                    <img src="Assets/placeholder2.jpg"> 
                    <div class="material-frame-title">Sample Title</div>
                    <div class="material-frame-subtitle">Sample Subtitle</div>
                    <div class="material-frame-description">Unavailable</div>
                </div>

                <div class="material-frame" onclick="openMaterialModal('Sample Title', 'Sample Subtitle', 'Sample Description', 'Available', 'Detail 1', 'Detail 2', 'Detail 3', 'Detail 4')">
                    <img src="Assets/placeholder1.jpg"> 
                    <div class="material-frame-title">Sample Title</div>
                    <div class="material-frame-subtitle">Sample Subtitle</div>
                    <div class="material-frame-description">Available</div>
                </div>

                <div class="material-frame" onclick="openMaterialModal('Sample Title', 'Sample Subtitle', 'Sample Description', 'Available', 'Detail 1', 'Detail 2', 'Detail 3', 'Detail 4')">
                    <img src="Assets/placeholder3.jpg"> 
                    <div class="material-frame-title">Sample Title</div>
                    <div class="material-frame-subtitle">Sample Subtitle</div>
                    <div class="material-frame-description">Available</div>
                </div>

                <div class="material-frame" onclick="openMaterialModal('Sample Title', 'Sample Subtitle', 'Sample Description', 'Available', 'Detail 1', 'Detail 2', 'Detail 3', 'Detail 4')">
                    <img src="Assets/placeholder.jpg"> 
                    <div class="material-frame-title">Sample Title</div>
                    <div class="material-frame-subtitle">Sample Subtitle</div>
                    <div class="material-frame-description">Available</div>
                </div>
            </div>

            <div class="material-modal-overlay" id="material-modal">
                <div class="material-modal">
                    <button class="material-modal-close" onclick="closeMaterialModal()">&times;</button>
                    <div class="material-modal-left">
                        <img src="Assets/placeholder2.jpg">
                        <div id="material-modal-status" class="material-modal-status">Available</div>
                    </div>
                    <div class="material-modal-right">
                        <div id="material-modal-title" class="material-modal-right-title">Title Here</div>
                        <div id="material-modal-detail1" class="material-modal-details">Detail 1: Information</div>
                        <div id="material-modal-detail2" class="material-modal-details">Detail 2: Information</div>
                        <div id="material-modal-detail3" class="material-modal-details">Detail 3: Information</div>
                        <div id="material-modal-detail4" class="material-modal-details">Detail 4: Information</div>
                    </div>
                </div>
            </div>

            <div class="policies-container">
                <h2 class="txtPolicies" id="section-policies"> Policies </h2>
                <div class="policy-section">
                    <h2 class="policy-section-header">1. Booking Policy</h2>
                    <div class="policy-item"><span>Advance Booking:</span> Appointments should be booked at least [X days/weeks] in advance to ensure availability.</div>
                    <div class="policy-item"><span>Required Information:</span> Customers must provide accurate details during booking, including location, service type, and contact information.</div>
                </div>
                <div class="policy-section">
                    <h2 class="policy-section-header">2. Cancellation and Rescheduling Policy</h2>
                    <div class="policy-item"><span>Cancellation Deadline:</span> Cancellations must be made at least [X hours/days] before the appointment. Failure to do so may result in a cancellation fee.</div>
                    <div class="policy-item"><span>Rescheduling Policy:</span> Rescheduling is allowed with at least [X hours/days] notice, subject to availability.</div>
                </div>
                <div class="policy-section">
                    <h2 class="policy-section-header">3. Payment Policy</h2>
                    <div class="policy-item"><span>Deposit:</span> A [percentage or fixed amount] deposit is required to secure your appointment, payable via [list payment methods].</div>
                    <div class="policy-item"><span>Full Payment:</span> The remaining balance is due upon project completion or as per the agreement.</div>
                    <div class="policy-item"><span>Accepted Payment Methods:</span> We accept cash, bank transfers, and online payment methods like [specific platforms].</div>
                </div>
                <div class="policy-section">
                    <h2 class="policy-section-header">4. Warranty Policy</h2>
                    <div class="policy-item"><span>Workmanship Warranty:</span> We provide a [X-year] warranty on all installations for defects in materials and workmanship.</div>
                    <div class="policy-item"><span>Exclusions:</span> The warranty does not cover damages caused by misuse, accidents, or natural wear and tear.</div>
                </div>
                <div class="policy-section">
                    <h2 class="policy-section-header">5. Service Area Policy</h2>
                    <div class="policy-item"><span>Covered Areas:</span> We primarily serve [list of locations]. If you’re outside this area, additional charges may apply for travel.</div>
                    <div class="policy-item"><span>Additional Fees:</span> Projects outside the service area may incur extra travel or transportation fees.</div>
                </div>
                <div class="policy-section">
                    <h2 class="policy-section-header">6. Project Preparation Policy</h2>
                    <div class="policy-item"><span>Customer Responsibilities:</span> Customers must ensure the installation area is clear, accessible, and free of obstacles before the scheduled appointment.</div>
                    <div class="policy-item"><span>Site Access:</span> If our team is unable to access the site, a rebooking fee may apply.</div>
                </div>
                <div class="policy-section">
                    <h2 class="policy-section-header">7. Privacy Policy</h2>
                    <div class="policy-item"><span>Data Usage:</span> We collect and store customer information solely for booking and service purposes. Your data will not be shared with third parties without consent.</div>
                    <div class="policy-item"><span>Security Measures:</span> We use secure platforms to protect your personal and payment information.</div>
                </div>
            </div>
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