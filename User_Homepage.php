<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['username']);

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mirror Your World</title>

    <link rel="stylesheet" href="Style/Required.css" />
    <link rel="stylesheet" href="Style/User_HomepageCSS.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <!-- Check if user has logged out -->
    <?php if (isset($_GET['logout']) && $_GET['logout'] == 'true'): ?>
        <script>
            Swal.fire({
                title: 'Logged Out',
                text: 'You have successfully logged out.',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        </script>
    <?php endif; ?>

    <!-- Your existing HTML content... -->

    <div class="navbar">
        <a href="User_Homepage.php">About</a>
        <a href="User_InquiryPage.php">FAQ</a>
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

    <a href="#" title="Go back to top" class="uparrow2">
        <img src="Assets/icon_uparrow.png" class="uparrow3">
        <span class="tooltip">Go back to top</span>
    </a>

    <div class="BGhome-container">
        <div class="text-container">
            <h1>Mirror Your <br>World.</h1>
            <p>Aluminum and Glass, <br /> Installation Services.</p>
            <button class="btnConsult" onclick="showConsultAlert()">Consult</button>
        </div>

        <img src="Assets/bg_HomePage.png" alt="Full-Screen Image" class="BGhome">
    </div>

    <!-- Page 2 -->
    <div class="PageTwo-container">
        <center>
            <div class="logoHeader-container">
                <img src="Assets/icon_Logo.png" class="logoHeader">
            </div>

            <div class="txtAbout-container">
                <h3 class="txtAbout"> At Mirror your world we take pride in being your trusted experts in aluminum
                    and glass installations. With years of experience and a dedication to excellence,
                    we specialize in transforming spaces with top-quality materials and craftsmanship.
                    Our mission is to deliver unparalleled service, ensuring that every project is not
                    only visually stunning but also durable and functional. </h3>
            </div>

            <div class="txtServices-container">
                <h2 class="txtServices"> What We Do </h2>
            </div>

            <div class="services-container">
                <div class="frames-container">
                    <!-- Frame 1 -->
                    <div class="frame" id="frame1">
                        <img src="Assets/icon_Aluminum.png" alt="Icon 1" class="frame-icon">
                        <h3 class="frame-title">Residential Installations</h3>
                        <p class="frame-description">Enhance the beauty and functionality of your home with our custom aluminum and glass installations. From sleek windows and doors to elegant glass partitions, we create solutions that complement your style and needs.</p>
                        <button class="learn-more-btn" onclick="openModal(1)">Learn More</button>
                    </div>

                    <!-- Frame 2 -->
                    <div class="frame" id="frame2">
                        <img src="Assets/icon_Glass.png" alt="Icon 2" class="frame-icon">
                        <h3 class="frame-title">Commercial Solutions</h3>
                        <p class="frame-description">Our expertise extends to commercial spaces, where we deliver professional installations for storefronts, office partitions, and more. We understand the importance of first impressions and functionality in business environments.</p>
                        <button class="learn-more-btn" onclick="openModal(2)">Learn More</button>
                    </div>

                    <!-- Frame 3 -->
                    <div class="frame" id="frame3">
                        <img src="Assets/icon_GlassandAluminum.png" alt="Icon 3" class="frame-icon">
                        <h3 class="frame-title">Custom Projects</h3>
                        <p class="frame-description">Have a unique vision? Our team is skilled in creating custom solutions tailored to your specific requirements. Whether it’s a bespoke glass facade or a complex aluminum structure, we’re here to bring your ideas to life.</p>
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

                <!-- Scrollable Container -->

                <br> <br>

                <div class="scrollable-container">
                    <div class="scroll-frame">
                        <h2 class="frame-title2">Our Story</h2>
                        <img src="Assets/icon_Book.png" alt="Icon 1" class="frame-icon">
                        <center>
                            <div class="frame-image">
                                <p class="frame-text">Founded in [2002], mirror your world was born out of a passion for precision and a commitment to innovation. What started as a small operation has grown into a leading provider of aluminum and glass solutions, thanks to our unwavering focus on customer satisfaction and quality workmanship.
                                </p>
                                <!-- <img src="Assets/pic-story.jpg" alt="Image 1" class="frame-background"> -->
                                <br>
                            </div>
                    </div>

                    <div class="scroll-frame">
                        <h2 class="frame-title2">Expertise</h2>
                        <img src="Assets/icon_Worker.png" alt="Icon 2" class="frame-icon">
                        <center>
                            <div class="frame-image">
                                <p class="frame-text">We offer a wide range of aluminum and glass solutions, including elegant residential installations, professional commercial projects, and custom designs. Our team is dedicated to delivering quality craftsmanship that enhances both aesthetics and functionality.</p>
                                <!-- <img src="Assets/pic-expertise.jpg" alt="Image 2" class="frame-background"> -->
                                <br>
                            </div>
                    </div>

                    <div class="scroll-frame">
                        <h2 class="frame-title2">Commitment</h2>
                        <img src="Assets/icon_Passion.png" alt="Icon 3" class="frame-icon">
                        <center>
                            <div class="frame-image">
                                <p class="frame-text">At mirror your world we are committed to providing exceptional service with integrity and transparency. We believe in building lasting relationships with our clients through reliable and honest work. Whether you’re renovating your home or designing a new commercial space, we’re here to help you every step of the way.</p>
                                <!-- <img src="Assets/pic-passion.jpg" alt="Image 3" class="frame-background"> -->
                                <br>
                            </div>
                    </div>
                </div>



            </div>
        </center>
    </div>

    <div class="Footer-container">
        <div class="text-container">
            <h1>Book a <br> consultation <br> now.</h1>
            <p>Got something specific to request? <button onclick="sendEmail()" class="btn-email2">Email Us</button> </p>
            <button class="btnConsult" onclick="showConsultAlert()">Consult</button>
        </div>
        <img src="Assets/bg_Footer.png" alt="Full-Screen Image" class="BGhome">
    </div>

</body>

<script>
    // Function to toggle the visibility of the dropdown content
    function toggleDropdown() {
        var dropdown = document.getElementById('dropdown1');
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }

    // Close the dropdown if the user clicks anywhere outside the dropdown
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-trigger') && !event.target.matches('.dropdown-trigger img')) {
            var dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(function(dropdown) {
                dropdown.style.display = 'none'; // Close the dropdown
            });
        }
    };

    // Frame and Modal
    function openModal(frameId) {
        const modal = document.getElementById('modal');
        const modalImage = document.getElementById('modal-image');
        const modalTitle = document.getElementById('modal-title');
        const modalDescription = document.getElementById('modal-description');

        if (frameId === 1) {
            modalImage.src = 'Assets/pic-expertise.jpg';
            modalTitle.innerHTML = 'We do Residential Installations!';
            modalDescription.innerHTML = 'Transform your space with our premium glass solutions. Whether youre looking for sleek glass panels, mirrors, or custom designs, we deliver clarity, elegance, and precision. Perfect for any style, our glass pieces are crafted to enhance your environment with a touch of modern sophistication.';
        } else if (frameId === 2) {
            modalImage.src = 'Assets/pic-passion.jpg';
            modalTitle.innerHTML = 'We do Commercial Solutions!';
            modalDescription.innerHTML = 'Add a touch of industrial strength and sleek design with our high-quality aluminum products. From frames to custom pieces, aluminum offers durability, lightweight flexibility, and a clean, contemporary look. Whether for structural or aesthetic purposes, our aluminum creations are built to last and impress.';
        } else if (frameId === 3) {
            modalImage.src = 'Assets/pic-story.jpg';
            modalTitle.innerHTML = 'We do Custom Projects';
            modalDescription.innerHTML = 'Experience the perfect fusion of elegance and strength with our glass and aluminum combinations. The smooth, reflective beauty of glass paired with the sturdy, modern feel of aluminum creates stunning designs that elevate any project. Custom-made to your specifications, these pieces are as versatile as they are beautiful.';
        }

        modal.style.display = 'block';
    }

    function closeModal() {
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

    function sendEmail() {
        window.open("https://mail.google.com/mail/?view=cm&fs=1&to=mirroryourworld.company.com", "_blank");
    }
</script>

</html>